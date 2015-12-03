<?php

namespace App\Policies;

use App\User;

class ClientPolicy
{
    /**
     * Define the rule on who can see the clients page and access them.
     *
     * @return boolean
     */
    public function viewClients()
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

    public function addClient()
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
}
