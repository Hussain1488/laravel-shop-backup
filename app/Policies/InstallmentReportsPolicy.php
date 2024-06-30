<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstallmentReportsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function viewAny(User $user)
    {
        return $user->can('installmentreports.index');
    }
    public function banktransaction(User $user)
    {
        return $user->can('installmentreports.banktransaction');
    }
    public function createinternalaccount(User $user)
    {
        return $user->can('installmentreports.createinternalaccount');
    }
    public function bankList(User $user)
    {
        return $user->can('installmentreports.banklist');
    }
}
