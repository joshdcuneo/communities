<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;

class CommunityPolicy
{
    public function view(User $user, Community $community): bool
    {
        return $community->isMember($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Community $community): bool
    {
        return $user->owns($community);
    }

    public function delete(User $user, Community $community): bool
    {
        return $user->owns($community);
    }

    public function restore(User $user, Community $community): bool
    {
        return $user->owns($community);
    }

    public function forceDelete(User $user, Community $community): bool
    {
        return false;
    }
}
