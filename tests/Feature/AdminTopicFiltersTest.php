<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTopicFiltersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_admin_can_filter_topics_by_name_and_target_score(): void
    {
        $family = Topic::create([
            'name' => 'Gia đình',
            'target_score' => 450,
        ]);
        Topic::create([
            'name' => 'Công việc',
            'target_score' => 650,
        ]);
        Topic::create([
            'name' => 'Du lịch',
            'target_score' => 990,
        ]);

        $this->get('/admin/topics?search=Gia&target_score=450')
            ->assertOk()
            ->assertSee('Gia đình')
            ->assertSee('TOEIC 450')
            ->assertDontSee('Công việc')
            ->assertDontSee('Du lịch');

        $this->get('/admin/topics?target_score=650')
            ->assertOk()
            ->assertSee('Công việc')
            ->assertDontSee('Gia đình')
            ->assertDontSee('Du lịch');

        $response = $this->get('/admin/topics');
        $content = $response->getContent();
        $familyPosition = strpos($content, '#'.$family->id);
        $workPosition = strpos($content, '#2');
        $travelPosition = strpos($content, '#3');

        $this->assertLessThan($workPosition, $familyPosition);
        $this->assertLessThan($travelPosition, $workPosition);
    }
}
