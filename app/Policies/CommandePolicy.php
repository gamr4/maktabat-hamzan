<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;

class CommandePolicy
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
    public function view(User $user, Commande $commande): bool
    {
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true)) {
            return true;
        }

        if ($user->isClient()) {
            return $user->clients->pluck('code_client')->contains($commande->code_client);
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
    public function update(User $user, Commande $commande): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commande $commande): bool
    {
        return $user->isAdmin();
    }
}
