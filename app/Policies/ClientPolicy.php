<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Client $client): bool
    {
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true)) {
            return true;
        }

        if ($user->isClient()) {
            return $user->clients->contains('code_client', $client->code_client);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Client $client): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $client): bool
    {
        return $user->isAdmin();
    }
}
