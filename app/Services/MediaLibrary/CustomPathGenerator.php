<?php


namespace App\Services\MediaLibrary;

use Morilog\Jalali\Jalalian;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;


class CustomPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . '/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . '/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . '/responsive-images/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $prefix = $this->merge_path(
            $this->date_prefix(),
            strtolower(class_basename($media->model_type)),
            $media->model->id
        );
        switch ($media->collection_name) {
            case 'default':
                return $this->merge_path($prefix, $media->id);
            default:
                return $this->merge_path($prefix, $media->collection_name, $media->id);
        }
    }

    private function merge_path(...$paths): string
    {
        $result = '';
        foreach ($paths as $path)
            $result .= ('/' . $path);
        return $result;
    }

    private function date_prefix(): string
    {
        return Jalalian::now()->format('Y/m');
    }
}
