<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Users that have requested this role
     */
    public function requestUsers()
    {
        return $this->belongsToMany('App\Models\User', 'role_user_requested');
    }

    /**
     * The users that have been assigned to this role
     */
    public function assignedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'role_user_assigned')
            ->withPivot('subrole');
    }
}
