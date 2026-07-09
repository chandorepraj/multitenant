<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class
        )->withPivot([
            'role',
            'joined_at',
        ])->withTimestamps();
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
