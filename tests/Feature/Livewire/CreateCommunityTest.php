<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CreateCommunity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateCommunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_renders_component()
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('community.create'))
            ->assertSeeLivewire(CreateCommunity::class);
    }

    public function test_route_requires_authentication()
    {
        $this->get(route('community.create'))
            ->assertRedirect(route('login'));
    }

    public function test_component_renders_successfully()
    {
        Livewire::test(CreateCommunity::class)
            ->assertStatus(200);
    }

    public function test_can_create_a_community()
    {
        $this->actingAs($user = User::factory()->create());

        $this->assertNull($user->ownedCommunities()->first());

        Livewire::test(CreateCommunity::class)
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
            ->test(CreateCommunity::class)
            ->set('description', 'This is my community.')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_description_is_required()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateCommunity::class)
            ->set('name', 'My Community')
            ->call('save')
            ->assertHasErrors(['description' => 'required']);
    }

    public function test_form_is_reset_after_save()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateCommunity::class)
            ->set('name', 'My Community')
            ->set('description', 'This is my community.')
            ->call('save')
            ->assertSet('name', '')
            ->assertSet('description', '');
    }

    public function test_flash_message_is_set_after_save()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateCommunity::class)
            ->set('name', 'My Community')
            ->set('description', 'This is my community.')
            ->call('save')
            ->assertSessionHas('status', 'Community created.');
    }

    public function test_redirects_to_dashboard_after_save()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateCommunity::class)
            ->set('name', 'My Community')
            ->set('description', 'This is my community.')
            ->call('save')
            ->assertRedirect(route('dashboard'));
    }
}
