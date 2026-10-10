<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGrant extends Model
{
    public $timestamps = false;

    protected $fillable = ['payment_id', 'benefit', 'quantity', 'granted_at'];

    protected $casts = ['quantity' => 'integer', 'granted_at' => 'datetime'];
}
