<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NFCCard;

class OrderRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'nfc_card_id',
        'business_id',
        'quantity',
        'price',
        'card_logo',
        'designation',
        'phoneno',
        'email',
        'shipping_address',
        'user_id',
        'status',
        'created_by'
    ];
    
    

    public static function OrderStatus()
    {
        $statusOption = [
            'in progress' => 'In Progress',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
        ];
        return $statusOption;
    }

}
