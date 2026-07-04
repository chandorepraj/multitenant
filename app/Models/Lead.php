<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    //
     protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'source',
        'tenant_id',
        'is_converted',
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
