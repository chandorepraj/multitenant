<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
    ];

    public static function record(string $action,string $description,$subject = null) 
    {
        static::create([
            'tenant_id' => session('tenant_id'),
            'user_id' => auth()->user()->id,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'description' => $description,
        ]);
    }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);

    }
    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
