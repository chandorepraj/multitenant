<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    //
    protected $fillable = [
        'email',
        'tenant_id',
        'token',
        'role',
        'is_valid'
    ];
}
