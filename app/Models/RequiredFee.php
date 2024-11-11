<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredFee extends Model
{
    use HasFactory;

    protected $table = 'requiredfees';

    protected $fillable = [
        'id',
        'amount',
        'purpose_code',
        'created_at',
        'updated_at',
    ];

    public function purpose()
    {
        return $this->belongsTo(Purpose::class, 'purpose_code', 'code');
    }
}
