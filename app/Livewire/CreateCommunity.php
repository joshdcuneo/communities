<?php

namespace App\Livewire;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class CreateCommunity extends Component
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

        $community->addMember(Auth::user());

        session()->flash('status', 'Community created.');

        $this->reset();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.create-community');
    }
}
