<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Customer extends Model
{
    //
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'tenant_id',
        'created_by',
    ];
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
