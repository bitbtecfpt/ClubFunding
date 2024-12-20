<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    use HasFactory;

    protected $table = 'purposes';

    protected $fillable = [
        'id',
        'name', 
        'code', 
        'note', 
        'prefix', 
        'bank_id', 
        'created_at', 
        'updated_at'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
