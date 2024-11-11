<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'banks';

    protected $fillable = [
        'id',
        'bank_account',
        'bank_name',
        'bank_fullname',
        'bank_username',
        'created_at',
        'updated_at',
    ];

}
