<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CommunityCreatePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommunityCreatePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_renders_component()
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('community.create'))
            ->assertSeeLivewire(CommunityCreatePage::class);
    }

    public function test_route_requires_authentication()
    {
        $this->get(route('community.create'))
            ->assertRedirect(route('login'));
    }

    public function test_component_renders_successfully()
    {
        Livewire::test(CommunityCreatePage::class)
            ->assertStatus(200);
    }

    public function test_can_create_a_community()
    {
        $this->actingAs($user = User::factory()->create());

        $this->assertNull($user->ownedCommunities()->first());

        Livewire::test(CommunityCreatePage::class)
            ->set('name', 'My Community')
            ->set('description', 'This is my community.')
            ->call('save');

        $community = $user->ownedCommunities()->first();
        $this->assertNotNull($community);
        $this->assertEquals('My Community', $community->name);
        $this->assertEquals('This is my community.', $community->description);
    }

    public function test_name_is_required()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CommunityCreatePage::class)
            ->set('description', 'This is my community.')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_description_is_required()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CommunityCreatePage::class)
            ->set('name', 'My Community')
            ->call('save')
            ->assertHasErrors(['description' => 'required']);
    }

    public function test_form_is_reset_after_save()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CommunityCreatePage::class)
            ->set('name', 'My Community')
            ->set('description', 'This is my community.')
            ->call('save')
            ->assertSet('name', '')
            ->assertSet('description', '');
    }

    public function test_redirects_to_community_list_after_save()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CommunityCreatePage::class)
            ->set('name', 'My Community')
            ->set('description', 'This is my community.')
            ->call('save')
            ->assertRedirect(route('community.index'));
    }
}
