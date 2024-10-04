<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'min',
        'max',
        'price',
        'created_by',
    ];
}
