<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    private const PARTS_OF_SPEECH = [
        'n' => '(n) Danh từ',
        'v' => '(v) Động từ',
        'adj' => '(adj) Tính từ',
        'adv' => '(adv) Trạng từ',
        'prep' => '(prep) Giới từ',
        'pron' => '(pron) Đại từ',
        'conj' => '(conj) Liên từ',
        'interj' => '(interj) Thán từ',
        'det' => '(det) Từ hạn định',
        'aux' => '(aux) Trợ động từ',
    ];

    private const PART_OF_SPEECH_ALIASES = [
        'noun' => 'n',
        'verb' => 'v',
        'adjective' => 'adj',
        'adverb' => 'adv',
        'preposition' => 'prep',
        'pronoun' => 'pron',
        'conjunction' => 'conj',
        'interjection' => 'interj',
        'determiner' => 'det',
        'auxiliary verb' => 'aux',
    ];

    protected $fillable = [
        'topic_id',
        'word',
        'part_of_speech',
        'phonetic',
        'meaning',
        'example',
        'family',
        'synonyms',
        'synonyms_meaning',
        'antonyms',
        'antonyms_meaning',
        'image',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function partOfSpeechOptions(): array
    {
        return self::PARTS_OF_SPEECH;
    }

    public static function normalizePartOfSpeech(?string $partOfSpeech): ?string
    {
        if ($partOfSpeech === null) {
            return null;
        }

        $value = mb_strtolower(trim($partOfSpeech));
        if (preg_match('/^\(([^)]+)\)/', $value, $matches)) {
            $value = trim($matches[1]);
        }

        return self::PART_OF_SPEECH_ALIASES[$value] ?? $value;
    }

    public static function partOfSpeechLabel(?string $partOfSpeech): string
    {
        $normalized = self::normalizePartOfSpeech($partOfSpeech);

        return self::PARTS_OF_SPEECH[$normalized] ?? (string) $partOfSpeech;
    }

    public function getPartOfSpeechLabelAttribute(): string
    {
        return self::partOfSpeechLabel($this->part_of_speech);
    }
}
