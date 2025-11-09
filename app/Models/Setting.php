<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'number' => (float) $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, string $type = 'string', ?string $description = null): void
    {
        $setting = static::where('key', $key)->first();

        $data = [
            'key' => $key,
            'value' => match($type) {
                'json' => json_encode($value),
                'boolean' => $value ? '1' : '0',
                default => (string) $value,
            },
            'type' => $type,
        ];

        if ($description) {
            $data['description'] = $description;
        }

        if ($setting) {
            $setting->update($data);
        } else {
            static::create($data);
        }
    }

    /**
     * Get USD to KSh conversion rate
     */
    public static function getUsdToKshRate(): float
    {
        return static::get('usd_to_ksh_rate', 140.0);
    }
}

