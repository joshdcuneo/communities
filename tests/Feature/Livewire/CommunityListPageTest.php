<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CommunityListPage;
use App\Models\Community;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommunityListPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_renders_component()
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('community.index'))
            ->assertSeeLivewire(CommunityListPage::class);
    }

    public function test_route_requires_authentication()
    {
        $this->get(route('community.index'))
            ->assertRedirect(route('login'));

    }

    public function test_component_renders_successfully()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CommunityListPage::class)
            ->assertStatus(200);
    }

    public function test_lists_communities()
    {
        $this->actingAs($user = User::factory()->create());
        $communities = Community::factory()->count(3)->hasAttached($user)->create();

        $page = Livewire::test(CommunityListPage::class)
            ->assertCount('communities', 3)
            ->assertSeeInOrder($communities->pluck('name')->toArray())
            ->assertSeeInOrder($communities->pluck('description')->toArray());
    }

    public function test_does_not_list_communities_the_user_is_not_a_member_of()
    {
        $this->actingAs(User::factory()->create());
        Community::factory()->count(3)->create();

        Livewire::test(CommunityListPage::class)
            ->assertCount('communities', 0);
    }

    public function test_links_to_create_community_page()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CommunityListPage::class)
            ->assertSeeHtml('href="'.route('community.create').'"');
    }
}
