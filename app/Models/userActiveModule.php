<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userActiveModule extends Model
{
    use HasFactory;
    protected $table = 'user_module';
    protected $fillable = ['user_id','module','active'];

    public static function getActiveModule()
    {
        return userActiveModule::where('module', '!=', 'QRCode')->where('active',1)->get();
    }
}
