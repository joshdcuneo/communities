<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CommunityShowPage;
use App\Models\Community;
use App\Models\CommunityInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CommunityShowPageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_route_renders_component()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        $this->get(route('community.show', $community))
            ->assertSeeLivewire(CommunityShowPage::class);
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

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->assertSuccessful();
    }

    public function test_can_see_community_details()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->assertSuccessful()
            ->assertSee($community->description);
    }

    public function test_can_see_community_members()
    {
        $this->actingAs(User::factory()->create());
        $community = Community::factory()->hasUsers(3)->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->assertSuccessful()
            ->assertSeeInOrder($community->users->pluck('email')->toArray())
            ->assertSeeInOrder($community->users->pluck('name')->toArray());
    }

    public function test_can_see_community_owner_as_a_member()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->assertSuccessful()
            ->assertSee($user->email)
            ->assertSee('You');
    }

    public function test_can_see_community_invitations()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->hasInvitations(3)->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->assertSuccessful()
            ->assertSeeInOrder(
                $community->invitations
                    ->map(function (CommunityInvitation $invitation) {
                        return $invitation->email;
                    })->toArray());
    }

    public function test_owner_can_add_member_by_email()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->set('newMemberEmail', $this->faker->unique()->safeEmail)
            ->call('addMemberByEmail')
            ->assertSuccessful();
    }

    public function test_unauthorized_users_cannot_add_members()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->hasUsers($user)->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->set('newMemberEmail', $this->faker->unique()->safeEmail)
            ->call('addMemberByEmail')
            ->assertForbidden();
    }

    public function test_add_member_by_email_requires_valid_email()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->set('newMemberEmail', 'not an email')
            ->call('addMemberByEmail')
            ->assertHasErrors(['newMemberEmail' => 'email']);
    }

    public function test_adds_an_existing_user_as_a_member()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();
        $newMember = User::factory()->create();

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->set('newMemberEmail', $newMember->email)
            ->call('addMemberByEmail')
            ->assertSuccessful();

        $this->assertTrue($community->load('users')->isMember($newMember));
    }

    public function test_can_invite_people_by_email_who_are_not_users()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();
        $invitationEmail = $this->faker->unique()->safeEmail;

        $this->assertTrue($community->invitations->isEmpty());

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->set('newMemberEmail', $invitationEmail)
            ->call('addMemberByEmail')
            ->assertSuccessful();

        $invitation = $community->invitations()->first();
        $this->assertSame($invitation->email, $invitationEmail);
    }

    public function test_inviting_people_does_not_create_duplicate_invitations()
    {
        $this->actingAs($user = User::factory()->create());
        $community = Community::factory()->forOwner($user)->create();
        $invitationEmail = $this->faker->unique()->safeEmail;

        $this->assertTrue($community->invitations->isEmpty());

        Livewire::test(CommunityShowPage::class, ['community' => $community])
            ->set('newMemberEmail', $invitationEmail)
            ->call('addMemberByEmail')
            ->set('newMemberEmail', $invitationEmail)
            ->call('addMemberByEmail');

        $this->assertSame(1, $community->invitations()->count());
    }

    public function test_owner_can_remove_members()
    {
        // TODO implement showing flash messages in toast!
        // TODO test
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_unauthorized_users_cannot_remove_members()
    {
        // TODO test
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_owner_can_remove_invitations()
    {
        // TODO implement
        $this->markTestIncomplete('Test not implemented yet.');
    }

    public function test_unauthorized_users_cannot_remove_invitations()
    {
        // TODO implement
        $this->markTestIncomplete('Test not implemented yet.');
    }
}
