<?php

use App\Models\Media;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;
use Silber\Bouncer\BouncerFacade;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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
            return '' . random_int(pow(10, $minLength - 1), (pow(10, $minLength) - 1));
        } catch (Exception $ex) {
            return random_number($minLength);
        }
    }
}

if (!function_exists('_settings')) {
    function _settings($group, $key = '*', $fresh = false)
    {
        /* $fresh ignore cached */
        return $key == '*'
            ? settings()->group($group)->all()
            : settings()->group($group)->get($key, null, $fresh);
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
        $units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('correct_uploaded_tmp_path')) {
    function correct_uploaded_tmp_path(string $fileId): string
    {
        return 'user/' . explode('-', $fileId)[0] . '/' . media_type(Storage::disk('tmp')->path($fileId)) . '/' . $fileId;
    }
}

if (!function_exists('media_type')) {
    function media_type(string $path): string
    {
        $mime = File::mimeType($path);
        foreach (Media::TYPES as $type)
            if ($type != Media::TYPE_OTHER && in_array($mime, __('enums.filetypes.' . $type)))
                return $type;
        return Media::TYPE_OTHER;
    }
}

if (!function_exists('insert_image_watermark')) {
    function insert_image_watermark()
    {

    }
}

if (!function_exists('_bouncer')) {
    function _bouncer(): object
    {
        return BouncerFacade::getFacadeRoot();
    }
}

if (!function_exists('db_try_catch')) {
    function db_try_catch(Closure $closure)
    {
        try {
            DB::beginTransaction();
            $result = $closure();
            DB::commit();
            return $result;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return responseJson([
                'message' => __('response.unknown')
            ], 500);
        }
    }
}

if (!function_exists('media_src')) {
    function media_src($fileId): string
    {
        return Storage::disk('tmp')->path($fileId);
    }
}

if (!function_exists('isJson')) {
    function isJson(string $str): bool
    {
        json_decode($str);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('timestampToDate')) {
    function timestampToDate($timestamp, $format = 'Y-m-d H:i:s'): string
    {
        return Carbon::createFromTimestamp($timestamp)->format($format);
    }
}

if (!function_exists('jalali_date')) {
    function jalali_date($date = 'now', $format = 'Y-m-d H:i:s'): string
    {
        return Jalalian::forge($date)->format($format);
    }
}

if (!function_exists('fake_model_image')) {
    function fake_model_image($model, $collection): string
    {
        [$width, $height] = [
            $model::setting("large_{$collection}_width") ?? 1024,
            $model::setting("large_{$collection}_height") ?? 1024,
        ];
        return "https://picsum.photos/{$width}/{$height}";
    }
}

if (!function_exists('class_to_slug')) {
    function class_to_slug(string $class, string $separator = '-'): string
    {
        return strtolower(implode($separator, preg_split('/(?=[A-Z])/', class_basename($class), -1, PREG_SPLIT_NO_EMPTY)));
    }
}

if (!function_exists('model_setting_name')) {
    function model_setting_name(string $class): string
    {
        return 'app_models_' . class_to_slug($class);
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): User
    {
        /** @var User $user */
        $user = auth()->user();
        return $user;
    }
}

if (!function_exists('add_media_as')) {
    /**
     * @throws FileIsTooBig
     */
    function add_media_as($entity, $collection, $path, $authId = null)
    {
        $authId = $authId ?? auth_user()->id;
        try {
            $media = $entity->addMedia(media_src($path))
                ->toMediaCollection($collection);
            $media->update(['user_id' => $authId]);
            return $media;
        } catch (FileDoesNotExist $e) {
            return null;
        } catch (FileIsTooBig $e) {
            throw $e;
        }
    }
}

if (!function_exists('add_media_from_tmp')) {
    /**
     * @param $entity
     * @param array $media
     * @param bool $deleteOnNull
     * @return array|null
     * @throws FileIsTooBig
     */
    function add_media_from_tmp($entity, array $media, bool $deleteOnNull = true): ?array
    {
        $result = [];
        foreach ($media as $collection => $path) {
            if (is_array($path)) {
                foreach ($path as $src) {
                    $result[$collection] = add_media_as($entity, $collection, $src);
                }
            } else {
                if (is_null($path)) {
                    if ($deleteOnNull && !is_null($entity->{$collection})) {
                        $entity->{$collection}->delete();
                        $result[$collection] = null;
                    }
                } else {
                    $result[$collection] = add_media_as($entity, $collection, $path);
                }
            }
        }
        $entity->refresh();
        return $result;
    }
}

if (!function_exists('en_to_fa')) {
    /**
     * Convert english digits to farsi.
     *
     * @param string $text
     * @return string
     */
    function en_to_fa(string $text): string
    {
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace($en_num, $fa_num, $text);
    }
}

if (!function_exists('fa_to_en')) {
    /**
     * Convert farsi/arabic digits to english.
     *
     * @param string $text
     * @return string
     */
    function fa_to_en(string $text): string
    {
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($fa_num, $en_num, $text);
    }
}

// if (!function_exists('parsePaymentVerificationException')) {
//     function parsePaymentVerificationException(\Shetabit\Multipay\Exceptions\InvalidPaymentException $exception): string
//     {
//         $message = $exception->getCode() . ' | ';
//         switch ($exception->getCode()) {
//             case 101:
//                 return $message . str_replace('PaymentVerification', 'تاییدیه پرداخت', $exception->getMessage());
//             default:
//                 return $message . $exception->getMessage();
//         }
//     }
// }
