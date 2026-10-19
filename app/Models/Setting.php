<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function getDeliveryFee()
    {
        $setting = self::where('key', 'delivery_fee')->first();
        return $setting ? (float) $setting->value : 7.00;
    }

    public static function getFreeDeliveryThreshold()
    {
        $setting = self::where('key', 'free_delivery_threshold')->first();
        return $setting ? (float) $setting->value : 100.00;
    }

    public static function getWhatsAppNumber()
    {
        $phone = self::get('phone', '+21623456789');
        $clean = preg_replace('/[^0-9]/', '', $phone);
        return $clean ?: '21623456789';
    }
}
