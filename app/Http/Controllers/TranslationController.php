<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class TranslationController extends Controller
{
    public function index()
    {
        return view('translate');
    }

    public function translate(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:5000',
            'direction' => 'required|in:en-vi,vi-en',
        ]);

        $text = trim($validated['text']);
        [$source, $target] = explode('-', $validated['direction']);
        $word = $this->findDictionaryEntry($text, $source);
        $alreadyInTargetLanguage = ! $word && $this->isAlreadyInTargetLanguage($text, $target);

        if ($word) {
            $translation = $source === 'en' ? $word->meaning : $word->word;
        } elseif ($alreadyInTargetLanguage) {
            $translation = $text;
        } else {
            $translation = $this->translateWithLibreTranslate($text, $source, $target);
        }

        return view('translate', [
            'input' => $text,
            'direction' => $validated['direction'],
            'result' => $translation,
            'dictionaryWord' => $word,
            'alreadyInTargetLanguage' => $alreadyInTargetLanguage,
        ]);
    }

    private function isAlreadyInTargetLanguage(string $text, string $target): bool
    {
        if ($this->findDictionaryEntry($text, $target)) {
            return true;
        }

        if ($target === 'vi' && preg_match('/[ăâđêôơưáàảãạấầẩẫậắằẳẵặéèẻẽẹếềểễệíìỉĩịóòỏõọốồổỗộớờởỡợúùủũụứừửữựýỳỷỹỵ]/iu', $text)) {
            return true;
        }

        return $this->detectLanguage($text) === $target;
    }

    private function detectLanguage(string $text): ?string
    {
        $baseUrl = rtrim((string) config('services.libretranslate.url'), '/');
        $payload = ['q' => $text];
        $apiKey = config('services.libretranslate.api_key');

        if (parse_url($baseUrl, PHP_URL_HOST) === 'libretranslate.com' && blank($apiKey)) {
            return null;
        }

        if (filled($apiKey)) {
            $payload['api_key'] = $apiKey;
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->post($baseUrl.'/detect', $payload);
        } catch (ConnectionException $exception) {
            report($exception);

            return null;
        }

        if (! $response->successful()) {
            report('LibreTranslate language detection returned HTTP '.$response->status());

            return null;
        }

        $detections = $response->json();
        if (! is_array($detections) || $detections === []) {
            return null;
        }

        $bestMatch = collect($detections)
            ->filter(fn ($detection) => is_array($detection)
                && isset($detection['language'], $detection['confidence'])
                && is_numeric($detection['confidence']))
            ->sortByDesc(fn (array $detection) => (float) $detection['confidence'])
            ->first();

        if (! $bestMatch || (float) $bestMatch['confidence'] < 60) {
            return null;
        }

        return $bestMatch['language'];
    }

    private function findDictionaryEntry(string $text, string $source): ?Word
    {
        $column = $source === 'en' ? 'word' : 'meaning';

        return Word::query()
            ->whereRaw('LOWER('.$column.') = ?', [mb_strtolower($text)])
            ->first();
    }

    private function translateWithLibreTranslate(string $text, string $source, string $target): string
    {
        $url = rtrim((string) config('services.libretranslate.url'), '/').'/translate';
        $payload = [
            'q' => $text,
            'source' => $source,
            'target' => $target,
            'format' => 'text',
        ];
        $apiKey = config('services.libretranslate.api_key');

        if (parse_url($url, PHP_URL_HOST) === 'libretranslate.com' && blank($apiKey)) {
            throw ValidationException::withMessages([
                'text' => 'LibreTranslate đang dùng dịch vụ hosted và cần API key. Hãy cấu hình LIBRETRANSLATE_API_KEY trong file .env để dịch từ chưa có trong từ điển và câu/đoạn văn.',
            ]);
        }

        if (filled($apiKey)) {
            $payload['api_key'] = $apiKey;
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->post($url, $payload);
        } catch (ConnectionException $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'text' => 'Không kết nối được dịch vụ dịch. Hãy kiểm tra kết nối mạng hoặc cấu hình LibreTranslate.',
            ]);
        }

        if (! $response->successful()) {
            report('LibreTranslate returned HTTP '.$response->status());

            $message = in_array($response->status(), [401, 403], true)
                ? 'Dịch vụ LibreTranslate yêu cầu API key hợp lệ. Hãy cấu hình LIBRETRANSLATE_API_KEY.'
                : 'Dịch vụ dịch đang gặp lỗi. Vui lòng thử lại sau.';

            throw ValidationException::withMessages(['text' => $message]);
        }

        $translation = $response->json('translatedText');

        if (! is_string($translation) || trim($translation) === '') {
            report('LibreTranslate returned an empty or invalid translation.');

            throw ValidationException::withMessages([
                'text' => 'Dịch vụ dịch không trả về kết quả hợp lệ. Vui lòng thử lại sau.',
            ]);
        }

        return $translation;
    }
}
