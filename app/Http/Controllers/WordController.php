<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class WordController extends Controller
{
    public function index(Request $request)
    {
        $words = Word::where('topic_id', $request->query('topic_id'))
            ->orderBy('id')
            ->get();

        return view('learn', compact('words'));
    }

    public function adminIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'topic_id' => 'nullable|integer|exists:topics,id',
            'part_of_speech' => 'nullable|string|max:255',
        ]);

        $wordsQuery = Word::with('topic')->orderBy('id');

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $wordsQuery->where(function ($query) use ($search) {
                $query->where('word', 'like', '%'.$search.'%')
                    ->orWhere('meaning', 'like', '%'.$search.'%')
                    ->orWhere('example', 'like', '%'.$search.'%');
            });
        }

        if (! empty($filters['topic_id'])) {
            $wordsQuery->where('topic_id', $filters['topic_id']);
        }

        if (! empty($filters['part_of_speech'])) {
            $wordsQuery->where('part_of_speech', $filters['part_of_speech']);
        }

        $words = $wordsQuery->get();
        $topics = Topic::orderBy('name')->get(['id', 'name']);
        $partsOfSpeech = Word::query()
            ->whereNotNull('part_of_speech')
            ->where('part_of_speech', '!=', '')
            ->distinct()
            ->orderBy('part_of_speech')
            ->pluck('part_of_speech');

        return view('admin.words.index', compact('words', 'topics', 'partsOfSpeech', 'filters'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $topics = Topic::orderBy('name')->get();

        return view('admin.words.create', [
            'topics' => $topics,
            'noTopics' => $topics->isEmpty(),
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (! Topic::exists()) {
            return redirect('/admin/words/create')
                ->withErrors(['topic_id' => 'Bạn phải thêm chủ đề để có thể thêm từ vựng mới']);
        }

        $this->rejectFailedImageUpload($request);

        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'word' => 'required|string|max:255',
            'part_of_speech' => 'required|string|max:255',
            'phonetic' => 'nullable|string|max:255',
            'meaning' => 'required|string|max:255',
            'example' => ['required', 'string', 'max:1000', function ($attribute, $value, $fail) use ($request) {
                if (! Str::contains($value, $request->input('word'), ignoreCase: true)) {
                    $fail('Câu ví dụ phải là một câu có chứa từ vựng đang thêm.');
                }
            }],
            'synonyms' => 'nullable|string|max:2000',
            'synonyms_meaning' => 'nullable|string|max:2000',
            'antonyms' => 'nullable|string|max:2000',
            'antonyms_meaning' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);
        $validated['part_of_speech'] = Word::normalizePartOfSpeech($validated['part_of_speech']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('words', 'public');
            if ($imagePath === false) {
                throw new RuntimeException('Không thể lưu ảnh từ vựng.');
            }

            $validated['image'] = $imagePath;
        }

        Word::create($validated);

        return redirect('/admin/words')->with('success', 'Thêm từ vựng thành công!');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $word = Word::findOrFail($id);
        $topics = Topic::orderBy('name')->get();

        return view('admin.words.edit', [
            'word' => $word,
            'topics' => $topics,
            'noTopics' => $topics->isEmpty(),
        ]);
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (! Topic::exists()) {
            return redirect('/admin/words/'.$id.'/edit')
                ->withErrors(['topic_id' => 'Bạn phải thêm chủ đề để có thể thêm từ vựng mới']);
        }

        $this->rejectFailedImageUpload($request);

        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'word' => 'required|string|max:255',
            'part_of_speech' => 'required|string|max:255',
            'phonetic' => 'nullable|string|max:255',
            'meaning' => 'required|string|max:255',
            'example' => ['required', 'string', 'max:1000', function ($attribute, $value, $fail) use ($request) {
                if (! Str::contains($value, $request->input('word'), ignoreCase: true)) {
                    $fail('Câu ví dụ phải là một câu có chứa từ vựng đang thêm.');
                }
            }],
            'synonyms' => 'nullable|string|max:2000',
            'synonyms_meaning' => 'nullable|string|max:2000',
            'antonyms' => 'nullable|string|max:2000',
            'antonyms_meaning' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'remove_image' => 'nullable|boolean',
        ]);
        $validated['part_of_speech'] = Word::normalizePartOfSpeech($validated['part_of_speech']);

        $word = Word::findOrFail($id);
        $previousImage = $word->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('words', 'public');
            if ($imagePath === false) {
                throw new RuntimeException('Không thể lưu ảnh từ vựng.');
            }

            $validated['image'] = $imagePath;
        } elseif ($request->boolean('remove_image')) {
            $validated['image'] = null;
        } else {
            unset($validated['image']);
        }

        unset($validated['remove_image']);
        $word->update($validated);

        if ($previousImage && $word->image !== $previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect('/admin/words')->with('success', 'Cập nhật từ vựng thành công!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $word = Word::findOrFail($id);
        $word->delete();
        if ($word->image) {
            Storage::disk('public')->delete($word->image);
        }

        return redirect('/admin/words')->with('success', 'Đã xóa từ vựng!');
    }
}
