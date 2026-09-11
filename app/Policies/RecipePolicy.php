<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecipePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Recipe $recipe): bool
    {
        if ($recipe->status === 1) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        return $user->id === $recipe->user_id;
    }

    public function viewPending(User $user, Recipe $recipe):bool
    {
        return $user->id === $recipe->user_id
        && $recipe->status === 0;
    }

    public function viewReject(User $user, Recipe  $recipe): bool
    {
        return $user->id === $recipe->user_id
        && $recipe->status === 2;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recipe $recipe)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recipe $recipe)
    {
        //
    }

    public function approve(User $user, Recipe $recipe)
    {
        return $user->role === 'admin';
    }
}
