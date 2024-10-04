<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name',
        'code',
        'discount',
        'limit',
        'description',
        'type',
        'minimum_spend',
        'maximum_spend',
        'per_user_limit',
        'expiry_date',
        'is_active'
    ];


    public function used_coupon()
    {
        return $this->hasMany('App\Models\UserCoupon', 'coupon', 'id')->count();
    }
    public function per_used_coupon($userid,$couponid)
    {
        return UserCoupon::where('user',$userid)->where('coupon',$couponid)->count();
    }

}
