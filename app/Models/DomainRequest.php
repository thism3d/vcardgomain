<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'business_id',
        'domain_name',
        'status',
    ];

    public function business()
    {
        return $this->hasOne('App\Models\Business', 'id', 'business_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public static $statues = [
        'Pending',
        'Approved',
        'Rejected'
    ];

}
