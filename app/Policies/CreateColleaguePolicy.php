<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreateColleaguePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function viewAny(User $user)
    {
        return $user->can('createcolleague.index');
    }

    public function create(User $user)
    {
        return $user->can('createcolleague.create');
    }
    public function store(User $user)
    {
        return $user->can('createcolleague.store');
    }
    public function createcreditoperator(User $user)
    {
        return $user->can('createcolleague.createcreditoperator');
    }
    public function createdocument(User $user)
    {
        return $user->can('createcolleague.createdocument');
    }
}
