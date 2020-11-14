<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Cache;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'email', 'password', 'phone'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

}
