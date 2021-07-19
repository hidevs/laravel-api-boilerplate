<?php


namespace App\Contracts;


trait HasSetting
{
    public static function setting($key, $fresh = false)
    {
        return _settings(model_setting_name(self::class), $key, $fresh);
    }
}
