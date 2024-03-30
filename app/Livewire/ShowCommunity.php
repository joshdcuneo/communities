<?php

namespace App\Livewire;

use App\Models\Community;
use App\Models\CommunityInvitation;
use App\Models\CommunityMember;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ShowCommunity extends Component
{
    public Community $community;

    #[Validate('email')]
    #[Validate('required')]
    public string $newMemberEmail = '';

    public function render()
    {
        return view('livewire.show-community');
    }

    public function addMemberByEmail(): void
    {
        $this->validate();

        match ($this->community->addMemberByEmail($this->newMemberEmail)::class) {
            CommunityMember::class => $this->community->load('members'),
            CommunityInvitation::class => $this->community->load('invitations')
        };

        $this->newMemberEmail = '';
    }
}
