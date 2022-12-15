<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'payment_method',
        'transaction_id',
        'virtual_account',
        'qr_link',
        'deep_link',
        'member_id',
        'status',
        'payment_expiry_time'
    ];
}
