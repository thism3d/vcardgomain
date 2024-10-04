<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user',
        'category',
        'business',
        'start_date',
        'end_date',
        'total_days',
        'total_cost',
        'payment_method',
        'status',
        'approval',
        'created_by'
    ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user');
    }
    public function categories()
    {
        return $this->hasOne('App\Models\BusinessCategory', 'id', 'category');
    }

    public function businesses()
    {
        return $this->hasOne('App\Models\Business', 'id', 'business');
    }

    public static $statuses = [
        0 => 'Pending',
        1 => 'Active',
        2 => 'Expired',
        3 => 'Declined'
    ];
   
}
