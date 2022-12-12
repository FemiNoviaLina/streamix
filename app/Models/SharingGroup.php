<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharingGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'packet',
        'duration',
        'price',
        'quota',
        'owner_id'
    ];
}
