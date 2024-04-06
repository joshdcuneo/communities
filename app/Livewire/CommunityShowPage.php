<?php

namespace App\Livewire;

use App\Models\Community;
use App\Models\User;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Masmerise\Toaster\Toaster;

class CommunityShowPage extends Component
{
    use AuthorizesRequests;

    public Community $community;

    #[Validate('email')]
    #[Validate('required')]
    public string $newMemberEmail = '';

    public function render(): View
    {
        return view('livewire.community-show-page');
    }

    public function mount(): void
    {
        $this->community->load('users');
    }

    public function addMemberByEmail(): void
    {
        $this->authorize('update', $this->community);

        $this->validate();

        if ($user = User::where('email', $this->newMemberEmail)->first()) {
            $this->community->users()->attach($user);
        } else {
            $this->community->invitations()->firstOrCreate([
                'email' => $this->newMemberEmail,
            ]);
        }

        $this->newMemberEmail = '';
    }

    public function removeMember(int $userId): RedirectResponse|Redirector|null
    {
        $this->authorize('update', $this->community);

        $user = User::findOrFail($userId);
        if ($this->community->isOwnedBy($user)) {
            Toaster::error('You cannot remove the community owner.');

            return null;
        }

        $this->community->users()->detach($user);
        if ($user->is(Auth::user())) {
            return redirect()->route('community.index');
        }

        Toaster::success("Removed member {$user->email}.");

        return null;
    }
}
