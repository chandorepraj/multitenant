<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    //
    protected $fillable = [
        'tenant_id',
        'created_by',
        'lead_id',
        'customer_id',
        'note'
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
}
