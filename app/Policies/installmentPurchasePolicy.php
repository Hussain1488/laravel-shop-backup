<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class installmentPurchasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function viewAny(User $user)
    {
        return $user->can('installmentpurchase.index');
    }
    public function view(User $user)
    {
        return $user->can('installmentpurchase.view');
    }
    public function create(User $user)
    {
        return $user->can('installmentpurchase.create');
    }
    public function update(User $user)
    {
        return $user->can('installmentpurchase.update');
    }
    public function delete(User $user)
    {
        return $user->can('installmentpurchase.delete');
    }
}
