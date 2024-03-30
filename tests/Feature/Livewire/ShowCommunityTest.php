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

    public function test_can_see_community_details()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        Livewire::test(ShowCommunity::class, ['community' => $community])
            ->assertSee($community->description);
    }

    public function test_can_see_community_members()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_can_see_community_invitations()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_owner_can_add_member_by_email()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_unauthorized_users_cannot_add_members()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_add_member_by_email_requires_valid_email()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_adding_members_creates_a_membership_if_email_is_registered()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_adding_members_creates_an_invitation_if_email_is_not_registered()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_adding_members_does_not_create_duplicate_invitations()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_owner_can_remove_members()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_unauthorized_users_cannot_remove_members()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_owner_can_remove_invitations()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_unauthorized_users_cannot_remove_invitations()
    {
        $this->markTestIncomplete('Test not implemented yet.');
    }
}
