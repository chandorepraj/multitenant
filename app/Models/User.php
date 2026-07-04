<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[Fillable(['name', 'email', 'password','tenant_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function tenants()
    {
        return $this->belongsToMany(
            Tenant::class
        )->withPivot([
            'role',
            'joined_at',
        ])->withTimestamps();
    }
    public function customers()
    {
        return $this->hasMany(
            Customer::class,
            'created_by'
        );
    }
    public function leads()
    {
        return $this->hasMany(
            Lead::class,
            'created_by'
        );
    }
     public function notes()
    {
        return $this->hasMany(
            Note::class,
            'created_by'
        );
    }
    public function activities()
    {
        return $this->hasMany(
            ActivityLog::class,
            'user_id'
        );
    }
    public function getCurrentRoleAttribute()
    {
         $tenant = $this->tenants()
        ->where('tenant_id', session('tenant_id'))
        ->first();

        return $tenant?->pivot?->role;
    }
}
