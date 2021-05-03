<?php


namespace App\Contracts;


trait HasSetting
{
    public static function setting($key, $fresh = false)
    {
        return _settings(self::class, $key, $fresh);
    }
}
