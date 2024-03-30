<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ShowCommunity;
use App\Models\Community;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowCommunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_renders_component()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        $this->get(route('community.show', $community))
            ->assertSeeLivewire(ShowCommunity::class);
    }

    public function test_route_requires_authentication()
    {
        $community = Community::factory()->create();

        $this->get(route('community.show', $community))
            ->assertRedirect(route('login'));
    }

    public function test_route_requires_authorization()
    {
        $this->actingAs(User::factory()->create());
        $community = Community::factory()->create();

        $this->get(route('community.show', $community))
            ->assertForbidden();
    }

    public function test_component_renders_successfully()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        Livewire::test(ShowCommunity::class, ['community' => $community])
            ->assertStatus(200);
    }

    public function test_can_see_community()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        Livewire::test(ShowCommunity::class, ['community' => $community])
            ->assertSee($community->description);
    }
}
