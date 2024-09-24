<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'amount',
        'bank_id',
        'created_at',
        'updated_at',
    ];
}
