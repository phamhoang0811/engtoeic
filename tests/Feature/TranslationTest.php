<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        Config::set('services.libretranslate.url', 'https://translate.example');
        Config::set('services.libretranslate.api_key', 'test-api-key');
    }

    public function test_homepage_links_to_the_translation_tool(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Phiên dịch / Tra cứu')
            ->assertSee('/translate')
            ->assertSee('Mục tiêu 450 TOEIC')
            ->assertSee('/topics?score=450')
            ->assertSee('/grammars?score=650')
            ->assertSee('Bài tập')
            ->assertSee('Luyện nghe')
            ->assertSee('/score-detail?score=450#exercises')
            ->assertSee('/score-detail?score=990#listening')
            ->assertSee('/score-detail?score=990');

        $this->get('/translate')
            ->assertOk()
            ->assertSee('row g-4 align-items-stretch', false)
            ->assertSee('Kết quả dịch / tra cứu')
            ->assertSee('col-lg-6', false)
            ->assertSee('Anh → Việt')
            ->assertSee('Việt → Anh')
            ->assertSee('Từ, câu hoặc đoạn văn');

        $this->get('/score-detail?score=450')
            ->assertOk()
            ->assertSee('id="exercises"', false)
            ->assertSee('id="listening"', false)
            ->assertSee('Bài tập')
            ->assertSee('Luyện nghe');
    }

    public function test_dictionary_lookup_returns_details_without_calling_translation_service(): void
    {
        Http::preventStrayRequests();

        $topic = Topic::create([
            'name' => 'Gia đình',
            'target_score' => 450,
        ]);
        Word::create([
            'topic_id' => $topic->id,
            'word' => 'relative',
            'part_of_speech' => 'n',
            'phonetic' => '/ˈrelətɪv/',
            'meaning' => 'họ hàng',
            'example' => 'A relative visited us.',
            'synonyms' => 'relation',
            'synonyms_meaning' => 'mối quan hệ',
            'antonyms' => 'stranger',
            'antonyms_meaning' => 'người lạ',
        ]);

        $this->post('/translate', [
            'text' => 'relative',
            'direction' => 'en-vi',
        ])->assertOk()
            ->assertSee('họ hàng')
            ->assertSee('(n) Danh từ')
            ->assertSee('/ˈrelətɪv/')
            ->assertSee('A relative visited us.')
            ->assertSee('relation')
            ->assertSee('stranger')
            ->assertSee('mối quan hệ')
            ->assertSee('người lạ');
    }

    public function test_text_is_translated_in_the_selected_direction_through_libretranslate(): void
    {
        Http::fake([
            'https://translate.example/translate' => Http::sequence()
                ->push(['translatedText' => 'Tôi sẽ tham dự cuộc họp vào ngày mai.'])
                ->push(['translatedText' => 'The family is very close.'])
                ->push(['translatedText' => 'cây cầu'])
                ->push(['translatedText' => 'Đây là một đoạn văn dài. Nó có nhiều câu.']),
        ]);

        $this->post('/translate', [
            'text' => 'I will attend the meeting tomorrow.',
            'direction' => 'en-vi',
        ])->assertOk()
            ->assertSee('Tôi sẽ tham dự cuộc họp vào ngày mai.');

        Http::assertSent(fn (ClientRequest $request) => $request->url() === 'https://translate.example/translate'
            && $request['q'] === 'I will attend the meeting tomorrow.'
            && $request['source'] === 'en'
            && $request['target'] === 'vi'
            && $request['api_key'] === 'test-api-key');

        $this->post('/translate', [
            'text' => 'Gia đình rất thân thiết.',
            'direction' => 'vi-en',
        ])->assertOk()
            ->assertSee('The family is very close.');

        Http::assertSent(fn (ClientRequest $request) => str_ends_with($request->url(), '/translate')
            && $request['source'] === 'vi'
            && $request['target'] === 'en');

        $this->post('/translate', [
            'text' => 'bridge',
            'direction' => 'en-vi',
        ])->assertOk()
            ->assertSee('cây cầu');

        $paragraph = 'This is a longer paragraph. It contains multiple sentences.';
        $this->post('/translate', [
            'text' => $paragraph,
            'direction' => 'en-vi',
        ])->assertOk()
            ->assertSee('Đây là một đoạn văn dài. Nó có nhiều câu.');

        Http::assertSent(fn (ClientRequest $request) => str_ends_with($request->url(), '/translate')
            && $request['q'] === 'bridge'
            && $request['source'] === 'en'
            && $request['target'] === 'vi');
        Http::assertSent(fn (ClientRequest $request) => str_ends_with($request->url(), '/translate')
            && $request['q'] === $paragraph
            && $request['source'] === 'en'
            && $request['target'] === 'vi');
    }

    public function test_text_already_in_the_target_language_is_returned_unchanged_in_both_directions(): void
    {
        Http::fake([
            'https://translate.example/detect' => Http::sequence()
                ->push([['language' => 'vi', 'confidence' => 98]])
                ->push([['language' => 'en', 'confidence' => 100]]),
        ]);
        Http::preventStrayRequests();

        $vietnamese = 'Xin chao toi dang hoc tieng Viet';
        $this->post('/translate', [
            'text' => $vietnamese,
            'direction' => 'en-vi',
        ])->assertOk()
            ->assertSee($vietnamese)
            ->assertSee('Nội dung đã ở ngôn ngữ đích');

        $english = 'Hello, I am studying English.';
        $this->post('/translate', [
            'text' => $english,
            'direction' => 'vi-en',
        ])->assertOk()
            ->assertSee($english)
            ->assertSee('Nội dung đã ở ngôn ngữ đích');

        Http::assertSentCount(2);
        Http::assertSent(fn (ClientRequest $request) => str_ends_with($request->url(), '/detect'));
    }

    public function test_missing_hosted_libretranslate_api_key_is_shown_in_the_result_panel(): void
    {
        Config::set('services.libretranslate.url', 'https://libretranslate.com');
        Config::set('services.libretranslate.api_key', null);
        Http::preventStrayRequests();

        $this->from('/translate')->followingRedirects()->post('/translate', [
            'text' => 'A word not in the admin dictionary',
            'direction' => 'en-vi',
        ])->assertOk()
            ->assertSee('Kết quả dịch / tra cứu')
            ->assertSee('cần API key');
    }

    public function test_translation_connection_failure_is_shown_to_the_user(): void
    {
        Http::fake([
            'https://translate.example/translate' => Http::failedConnection(),
        ]);

        $this->from('/translate')->post('/translate', [
            'text' => 'A long paragraph to translate.',
            'direction' => 'en-vi',
        ])->assertRedirect('/translate')
            ->assertSessionHasErrors('text');
    }
}
