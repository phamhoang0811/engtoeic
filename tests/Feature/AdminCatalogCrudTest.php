<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCatalogCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();
        $this->actingAs($admin);
    }

    public function test_admin_role_can_be_assigned_when_creating_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertSame('admin', $admin->role);
    }

    public function test_failed_image_upload_shows_the_php_upload_error_for_topics_and_words(): void
    {
        $failedImage = fn () => new UploadedFile(
            __FILE__,
            'image.jpg',
            'image/jpeg',
            UPLOAD_ERR_CANT_WRITE,
            true,
        );
        $message = 'Máy chủ không ghi được file ảnh tạm. Hãy kiểm tra thư mục tạm của PHP.';

        $this->post('/admin/topics', [
            'name' => 'Office',
            'target_score' => 450,
            'image' => $failedImage(),
        ])->assertSessionHasErrors(['image' => $message]);

        $topic = Topic::create([
            'name' => 'Office',
            'target_score' => 450,
        ]);

        $this->post('/admin/words', [
            'topic_id' => $topic->id,
            'word' => 'Contract',
            'part_of_speech' => '(n) Danh từ',
            'meaning' => 'Hợp đồng',
            'image' => $failedImage(),
        ])->assertSessionHasErrors(['image' => $message]);
    }

    public function test_admin_can_create_update_and_delete_a_topic_with_an_image(): void
    {
        $this->get('/admin/topics/create')->assertOk();

        $this->post('/admin/topics', [
            'name' => 'Office',
            'target_score' => 450,
            'description' => 'Office vocabulary',
            'image' => UploadedFile::fake()->image('office.png')->size(3072),
        ])->assertRedirect('/admin/topics');

        $topic = Topic::firstOrFail();
        $originalImage = $topic->image;
        Storage::disk('public')->assertExists($originalImage);

        $this->get("/admin/topics/{$topic->id}/edit")->assertOk();
        $this->put("/admin/topics/{$topic->id}", [
            'name' => 'Business Office',
            'target_score' => 650,
            'description' => 'Updated description',
            'image' => UploadedFile::fake()->image('business.png'),
        ])->assertRedirect('/admin/topics');

        $topic->refresh();
        $this->assertSame('Business Office', $topic->name);
        $this->assertSame(650, $topic->target_score);
        $this->assertNotSame($originalImage, $topic->image);
        Storage::disk('public')->assertMissing($originalImage);
        Storage::disk('public')->assertExists($topic->image);

        $updatedImage = $topic->image;
        $this->delete("/admin/topics/{$topic->id}")->assertRedirect('/admin/topics');
        Storage::disk('public')->assertMissing($updatedImage);
        $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
    }

    public function test_admin_can_create_update_and_delete_a_word_with_an_image(): void
    {
        $topic = Topic::create([
            'name' => 'Office',
            'target_score' => 450,
        ]);

        $this->get('/admin/words/create')
            ->assertOk()
            ->assertSee('multipart/form-data', false);

        $this->post('/admin/words', [
            'topic_id' => $topic->id,
            'word' => 'Contract',
            'part_of_speech' => '(n) Danh từ',
            'phonetic' => '/ˈkɒntrækt/',
            'meaning' => 'Hợp đồng',
            'example' => 'Review the contract.',
            'family' => 'Contractual',
            'image' => UploadedFile::fake()->image('contract.png')->size(3072),
        ])->assertRedirect('/admin/words');

        $word = Word::firstOrFail();
        $originalImage = $word->image;
        Storage::disk('public')->assertExists($originalImage);
        $this->assertSame('n', $word->part_of_speech);
        $this->assertSame('/ˈkɒntrækt/', $word->phonetic);

        $this->get("/admin/words/{$word->id}/edit")->assertOk();
        $this->put("/admin/words/{$word->id}", [
            'topic_id' => $topic->id,
            'word' => 'Agreement',
            'part_of_speech' => '(n) Danh từ',
            'phonetic' => '/əˈɡriːmənt/',
            'meaning' => 'Thỏa thuận',
            'example' => 'They reached an agreement.',
            'family' => 'Agree',
            'image' => UploadedFile::fake()->image('agreement.png'),
        ])->assertRedirect('/admin/words');

        $word->refresh();
        $this->assertSame('Agreement', $word->word);
        $this->assertNotSame($originalImage, $word->image);
        Storage::disk('public')->assertMissing($originalImage);
        Storage::disk('public')->assertExists($word->image);

        $this->get("/learn?topic_id={$topic->id}")
            ->assertOk()
            ->assertSee('Agreement')
            ->assertSee('(n) Danh từ')
            ->assertSee(asset('storage/'.$word->image));
        $updatedImage = $word->image;
        $this->delete("/admin/words/{$word->id}")->assertRedirect('/admin/words');
        Storage::disk('public')->assertMissing($updatedImage);
        $this->assertDatabaseMissing('words', ['id' => $word->id]);
    }
}
