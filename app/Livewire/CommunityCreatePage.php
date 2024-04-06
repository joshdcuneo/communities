<?php

namespace App\Livewire;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Masmerise\Toaster\Toaster;

class CommunityCreatePage extends Component
{
    #[Validate('required|min:3|max:255')]
    public string $name = '';

    #[Validate('required|min:10|max:1000')]
    public string $description = '';

    public function save(): RedirectResponse|Redirector
    {
        $this->validate();

        $community = Auth::user()->ownedCommunities()->create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $community->users()->attach(Auth::user());

        $this->reset();

        Toaster::success("Created community {$community->name}");

        return redirect()->route('community.index');
    }

    public function render()
    {
        return view('livewire.community-create-page');
    }
}
