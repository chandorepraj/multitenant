<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    //
    protected $fillable = [
        'title',
        'due_date',
        'description',
        'status',
        'priority',
        'lead_id',
        'customer_id',
        'tenant_id',
        'assigned_to',
        'created_by',
    ];
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function owner()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }
}
