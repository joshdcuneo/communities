<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Dashboard;
use App\Models\Community;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_renders_component()
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('dashboard'))
            ->assertSeeLivewire(Dashboard::class);
    }

    public function test_route_requires_authentication()
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));

    }

    public function test_component_renders_successfully()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(Dashboard::class)
            ->assertStatus(200);
    }

    public function test_lists_owned_communities()
    {
        $this->actingAs($user = User::factory()->create());
        $communities = Community::factory()->count(3)->forOwner($user)->create();

        $page = Livewire::test(Dashboard::class)
            ->assertCount('communities', 3);

        $communities->each(function ($community) use ($page) {
            $page->assertSee($community->name);
            $page->assertSee($community->description);
        });
    }

    public function test_does_not_list_other_users_communities_without_permission()
    {
        $this->actingAs(User::factory()->create());
        Community::factory()->count(3)->create();

        Livewire::test(Dashboard::class)
            ->assertCount('communities', 0);
    }

    public function test_links_to_create_community_page()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(Dashboard::class)
            ->assertSeeHtml('href="'.route('community.create').'"');
    }
}
