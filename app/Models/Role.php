<?php

namespace App\Models;



use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'guard_name'
    ];
}
