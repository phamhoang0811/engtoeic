<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use App\Models\Word;
use Database\Seeders\VocabularyCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class VocabularyFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
    }

    public function test_vocabulary_cannot_be_added_without_a_topic(): void
    {
        $message = 'Bạn phải thêm chủ đề để có thể thêm từ vựng mới';

        $this->get('/admin/words/create')
            ->assertOk()
            ->assertSee($message)
            ->assertSee('disabled', false);

        $this->post('/admin/words', [
            'word' => 'relative',
            'part_of_speech' => 'n',
            'meaning' => 'họ hàng',
        ])->assertRedirect('/admin/words/create')
            ->assertSessionHasErrors(['topic_id' => $message]);

        $this->assertDatabaseCount('words', 0);
    }

    public function test_admin_can_filter_vocabulary_by_word_topic_and_part_of_speech(): void
    {
        $family = Topic::create([
            'name' => 'Gia đình',
            'target_score' => 450,
        ]);
        $work = Topic::create([
            'name' => 'Công việc',
            'target_score' => 650,
        ]);

        Word::create([
            'topic_id' => $family->id,
            'word' => 'relative',
            'part_of_speech' => 'n',
            'meaning' => 'họ hàng',
            'example' => 'A relative visited us.',
        ]);
        Word::create([
            'topic_id' => $family->id,
            'word' => 'close',
            'part_of_speech' => 'adj',
            'meaning' => 'thân thiết',
            'example' => 'We are a close family.',
        ]);
        Word::create([
            'topic_id' => $work->id,
            'word' => 'colleague',
            'part_of_speech' => 'n',
            'meaning' => 'đồng nghiệp',
            'example' => 'My colleague is helpful.',
        ]);

        $this->get("/admin/words?search=relative&topic_id={$family->id}&part_of_speech=n")
            ->assertOk()
            ->assertSee('relative')
            ->assertDontSee('colleague')
            ->assertDontSee('close');

        $this->get("/admin/words?topic_id={$work->id}")
            ->assertOk()
            ->assertSee('colleague')
            ->assertDontSee('relative');
    }

    public function test_example_must_be_a_sentence_containing_the_vocabulary_word(): void
    {
        $topic = Topic::create([
            'name' => 'Gia đình',
            'target_score' => 450,
        ]);

        $this->from('/admin/words/create')->post('/admin/words', [
            'topic_id' => $topic->id,
            'word' => 'relative',
            'part_of_speech' => 'n',
            'meaning' => 'họ hàng',
            'example' => 'My family visited us.',
        ])->assertRedirect('/admin/words/create')
            ->assertSessionHasErrors(['example' => 'Câu ví dụ phải là một câu có chứa từ vựng đang thêm.']);

        $this->assertDatabaseCount('words', 0);
    }

    public function test_synonyms_and_antonyms_can_be_created_edited_and_shown_while_learning(): void
    {
        $topic = Topic::create([
            'name' => 'Gia đình',
            'target_score' => 450,
        ]);

        $this->get('/admin/words/create')
            ->assertOk()
            ->assertSee('for="phonetic"', false)
            ->assertSee('(n) Danh từ')
            ->assertSee('(adj) Tính từ')
            ->assertSee('Từ đồng nghĩa (=)')
            ->assertSee('Từ trái nghĩa');

        $this->post('/admin/words', [
            'topic_id' => $topic->id,
            'word' => 'relative',
            'part_of_speech' => 'n',
            'phonetic' => '/ˈrelətɪv/',
            'meaning' => 'họ hàng',
            'example' => 'A relative visited us.',
            'synonyms' => 'relation, family member',
            'synonyms_meaning' => 'mối quan hệ, thành viên gia đình',
            'antonyms' => 'stranger',
            'antonyms_meaning' => 'người lạ',
        ])->assertRedirect('/admin/words');

        $word = Word::firstOrFail();
        $this->assertSame('relation, family member', $word->synonyms);
        $this->assertSame('mối quan hệ, thành viên gia đình', $word->synonyms_meaning);
        $this->assertSame('stranger', $word->antonyms);
        $this->assertSame('người lạ', $word->antonyms_meaning);

        $this->get("/admin/words/{$word->id}/edit")
            ->assertOk()
            ->assertSee('relation, family member')
            ->assertSee('mối quan hệ, thành viên gia đình')
            ->assertSee('stranger');

        $this->put("/admin/words/{$word->id}", [
            'topic_id' => $topic->id,
            'word' => 'relative',
            'part_of_speech' => 'n',
            'phonetic' => '/ˈrelətɪv/',
            'meaning' => 'họ hàng',
            'example' => 'A relative visited us.',
            'synonyms' => 'kin, family member',
            'synonyms_meaning' => 'người thân, thành viên gia đình',
            'antonyms' => 'stranger',
            'antonyms_meaning' => 'người lạ',
        ])->assertRedirect('/admin/words');

        $this->get("/learn?topic_id={$topic->id}")
            ->assertOk()
            ->assertSee('kin, family member')
            ->assertSee('người thân, thành viên gia đình')
            ->assertSee('stranger')
            ->assertSee('data-pronounce="relative"', false)
            ->assertSee('(n) Danh từ')
            ->assertSee('/ˈrelətɪv/')
            ->assertSee('người lạ');

        $this->get('/admin/words')
            ->assertOk()
            ->assertSee('kin, family member')
            ->assertSee('(n) Danh từ')
            ->assertSee('stranger');
    }

    public function test_catalog_seeder_creates_five_topics_with_fifteen_words_each_and_preserves_images(): void
    {
        $familyTopic = Topic::create([
            'name' => 'Iphone 12',
            'target_score' => 450,
            'image' => 'topics/family.jpg',
        ]);
        $customPronunciation = Word::create([
            'topic_id' => $familyTopic->id,
            'word' => 'father',
            'part_of_speech' => 'n',
            'meaning' => 'bố',
            'phonetic' => '/custom pronunciation/',
        ]);

        $this->seed(VocabularyCatalogSeeder::class);
        $this->seed(VocabularyCatalogSeeder::class);

        $this->assertDatabaseCount('topics', 7);
        $this->assertDatabaseCount('words', 105);
        $this->assertSame('/custom pronunciation/', $customPronunciation->fresh()->phonetic);

        $familyTopic->refresh();
        $this->assertSame('Gia đình', $familyTopic->name);
        $this->assertSame('topics/family.jpg', $familyTopic->image);

        $this->assertDatabaseHas('topics', [
            'slug' => 'giao-tiep-kinh-doanh',
            'target_score' => 650,
        ]);
        $this->assertDatabaseHas('topics', [
            'slug' => 'quan-ly-du-an',
            'target_score' => 990,
        ]);

        foreach (Topic::all() as $topic) {
            $this->assertSame(15, $topic->words()->count(), $topic->name);

            foreach ($topic->words as $word) {
                $this->assertNotEmpty($word->phonetic, "Missing pronunciation for {$word->word}.");
                $this->assertTrue(
                    Str::contains($word->example, $word->word, ignoreCase: true),
                    "{$word->word} must appear in its example sentence.",
                );

                foreach ([
                    [$word->synonyms, $word->synonyms_meaning],
                    [$word->antonyms, $word->antonyms_meaning],
                ] as [$relatedWords, $relatedMeanings]) {
                    if (! $relatedWords) {
                        continue;
                    }

                    $meanings = array_map('trim', explode(',', (string) $relatedMeanings));
                    foreach (explode(',', $relatedWords) as $index => $relatedWord) {
                        $this->assertNotEmpty(
                            $meanings[$index] ?? null,
                            "Missing Vietnamese meaning for {$word->word}'s related word {$relatedWord}.",
                        );
                    }
                }
            }

            if ($topic->id !== $familyTopic->id) {
                $this->assertNull($topic->image);
            }
        }
    }
}
