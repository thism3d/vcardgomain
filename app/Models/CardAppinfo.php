<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardAppinfo extends Model
{
    use HasFactory;
    private static $appInfo = null;
    private static $flagApps = false;
    protected $table = 'card_app_infos';
    protected $fillable = [
        'business_id',
        'playstore_id',
        'appstore_id',
        'variant',
        'is_enabled',
        'created_by'
    ];
    public static function cardAppData($id)
    {
        if (self::$appInfo == null) {
            if (self::$flagApps === false) {

                $appDetail = CardAppinfo::where('business_id', $id)->first();
                self::$appInfo = $appDetail;
                self::$flagApps = true;
            }
        }
        return self::$appInfo;

    }
}
