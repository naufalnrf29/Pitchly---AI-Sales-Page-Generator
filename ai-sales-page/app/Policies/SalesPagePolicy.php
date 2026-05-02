<?php

namespace App\Policies;

use App\Models\SalesPage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalesPagePolicy
{
    /** Only the owner can do anything with their page. */
    private function owns(User $user, SalesPage $salesPage): bool
    {
        return $user->id === $salesPage->user_id;
    }

    public function viewAny(User $user): bool  { return true; }
    public function view(User $user, SalesPage $salesPage): bool   { return $this->owns($user, $salesPage); }
    public function create(User $user): bool   { return true; }
    public function update(User $user, SalesPage $salesPage): bool { return $this->owns($user, $salesPage); }
    public function delete(User $user, SalesPage $salesPage): bool { return $this->owns($user, $salesPage); }
}
