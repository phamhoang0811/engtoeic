<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_single_topic_uses_a_grid_column_instead_of_stretching_across_the_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Topic::create([
            'name' => 'Office',
            'target_score' => 450,
            'image' => 'topics/office.jpg',
        ]);

        $response = $this->get('/topics?score=450')
            ->assertOk()
            ->assertSee('col-12 col-sm-6 col-lg-3', false)
            ->assertSee('Bắt đầu học');

        $this->assertLessThan(
            strpos($response->getContent(), '<img'),
            strpos($response->getContent(), '<h5 class="fw-bold mb-0">Office</h5>'),
        );
    }

    public function test_topic_list_has_twelve_topics_per_page_and_keeps_score_when_paginating(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (range(1, 13) as $number) {
            Topic::create([
                'name' => 'Topic '.$number,
                'target_score' => 650,
            ]);
        }

        $this->get('/topics?score=650')
            ->assertOk()
            ->assertSee('Topic 1')
            ->assertSee('Topic 12')
            ->assertDontSee('Topic 13')
            ->assertSee('topics?score=650&amp;page=2', false);

        $this->get('/topics?score=650&page=2')
            ->assertOk()
            ->assertSee('Topic 13')
            ->assertDontSee('Topic 1</h5>', false);
    }
}
