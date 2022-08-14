<?php

namespace App\Models;



use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Model\User;

class Role extends SpatieRole
{
    public function role_admins()
    {
        return $this->hasMany(GroupAdmins::class);
    }
}
