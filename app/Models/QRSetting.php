<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'json',
        'slug',
        'total_scan',
        'template_id',
        'created_by'
    ];
    protected $table = 'qrsettings';
}
