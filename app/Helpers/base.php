<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

if (!function_exists('to_valid_mobile_number')) {
    function to_valid_mobile_number(string $mobile): string
    {
        return '+98' . substr($mobile, -10, 10);
    }
}

if (!function_exists('responseJson')) {
    function responseJson($data = [], $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        return response()->json($data, $status, $headers, $options);
    }
}

if (!function_exists('random_number')) {
    function random_number($minLength = 6): string
    {
        try {
            return ''.random_int(pow(10, $minLength - 1), (pow(10, $minLength) - 1));
        } catch (Exception $ex) {
            return random_number($minLength);
        }
    }
}

if (!function_exists('_settings')) {
    function _settings($group, $key, $fresh = false)
    {
        /* $fresh ignore cached */
        return settings()->group($group)->get($key, null, $fresh);
    }
}

if (!function_exists('translate_enums')) {
    function translate_enums(string $class, string $key, string $enum)
    {
        return __('enums.' . $class . '.' . $key . '.' . $enum);
    }
}

if (!function_exists('can_string')) {
    function can_string(string $action, ...$modelClasses): string
    {
        return 'can:' . $action . ',' . implode(',', $modelClasses);
    }
}

if (!function_exists('human_filesize')) {
    function human_filesize($size, $precision = 2): string
    {
        $units = ['B','kB','MB','GB','TB','PB','EB','ZB','YB'];
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('_bouncer')) {
    function _bouncer(): object
    {
        return \Silber\Bouncer\BouncerFacade::getFacadeRoot();
    }
}

if (!function_exists('db_try_catch')) {
    function db_try_catch(\Closure $closure)
    {
        try {
            DB::beginTransaction();
            $result = $closure();
            DB::commit();
            return $result;
        }
        catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw $exception;
        }
    }
}


if (!function_exists('model_resource')) {
    function model_resource($model, $type = 'Resource', string $path = 'App\\Http\\Resources\\'): string
    {
        return $path . class_basename($model) . $type;
    }
}

if (!function_exists('auth_resolver')) {
    function auth_resolver(Authenticatable $authenticatable, Closure $closure)
    {
        $oldUser = null;
        if (auth()->check()) {
            $oldUser = auth()->user();
            auth()->logout();
        }
        auth()->login($authenticatable);

        $result = $closure();

        if (auth()->check()) {
            auth()->logout();
            if ($oldUser)
                auth()->login($oldUser);
        }
        return $result;
    }
}
