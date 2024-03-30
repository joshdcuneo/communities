<?php

namespace App\Livewire;

use App\Models\Community;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * @var Collection<int, Community>
     */
    public Collection $communities;

    public function mount(): void
    {
        $this->communities = auth()->user()->ownedCommunities;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
