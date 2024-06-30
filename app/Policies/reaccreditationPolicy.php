<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class reaccreditationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function viewAny(User $user)
    {
        return $user->can('createcolleague.index');
    }

    public function reaccreditationStore(User $user)
    {
        return $user->can('createcolleague.createcreditoperator');
    }
    public function reaccreditationIndex(User $user)
    {
        return $user->can('createcolleague.reaccreditation.index');
    }
}
