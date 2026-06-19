<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'description'];

    // === ENCAPSULATION: Get setting value with type casting ===
    public function getTypedValue()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    // === STATIC HELPER: Get setting by key ===
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->getTypedValue() : $default;
    }

    // === STATIC HELPER: Set setting value ===
    public static function set(string $key, $value, string $type = 'string')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
            ]
        );
    }
}