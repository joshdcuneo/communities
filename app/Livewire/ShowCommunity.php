<?php

namespace App\Livewire;

use App\Models\Community;
use Livewire\Component;

class ShowCommunity extends Component
{
    public Community $community;

    public function render()
    {
        return view('livewire.show-community');
    }
}
