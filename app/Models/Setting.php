<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getDeliveryFee()
    {
        $setting = self::where('key', 'delivery_fee')->first();
        return $setting ? (float) $setting->value : 7.00; // 7 TND default
    }
}
