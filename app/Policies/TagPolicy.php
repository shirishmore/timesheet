<?php

namespace App\Policies;

use App\User;

class TagPolicy
{
    /**
     * Define who can add Tags
     *
     * @param User $user
     */
    public function add()
    {
        $allowed = ['Admin', 'Project Manager'];

        $userRoles = User::roles();

        foreach ($userRoles as $role) {
            if (in_array($role->role, $allowed)) {
                return true;
            }
        }

        return false;
    }

    public function delete()
    {
        $allowed = ['Admin'];

        $userRoles = User::roles();

        foreach ($userRoles as $role) {
            if (in_array($role->role, $allowed)) {
                return true;
            }
        }

        return false;
    }
}
