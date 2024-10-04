<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NFCCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'card_name',
        'price',
        'image',
        'created_by'
    ];
    protected $table = 'nfc_cards';
}
