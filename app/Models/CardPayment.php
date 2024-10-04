<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPayment extends Model
{
    use HasFactory;
    private static $cardPaymentData = null;
    private static $flagPayment = false;
    protected $fillable = [
        'business_id',
        'payment_amount',
        'content',
        'payment_status',
        'payment_type',  
        'is_enabled',  
        'created_by'
    ];

    public static function cardPaymentData($id)
    {
        if(self::$cardPaymentData == null) {
            if(self::$flagPayment === false){
                
                $cardPaymentDetail=CardPayment::where('business_id', $id)->first();
                self::$cardPaymentData = $cardPaymentDetail;
                self::$flagPayment =  true;
            }       
        }
        return self::$cardPaymentData;

    }
}
