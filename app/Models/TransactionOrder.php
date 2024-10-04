<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'request_amount',
        'request_user_id',
    ];

    public function getCompany()
    {
        return $this->hasOne('App\Models\User', 'id', 'request_user_id');
    }
    public static $status = [
        'Rejected',
        'In Progress',
        'Approved',
    ];
}
