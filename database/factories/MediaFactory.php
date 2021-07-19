<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;
    private $owner = null;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $simpleImage = public_path('favicon.png');

        $type = $this->faker->randomElement(Media::TYPES);
        $model = $type::find($this->faker->unique()->numberBetween(1, $type::count()));

        switch ($type) {
            case User::class:
                $this->owner = $model;
                $this->addMedia($model, 'avatar', fake_model_image($model, 'avatar'), true);
                break;
            default:
                break;
        }

        return [];
    }

    public function addMedia($model, $collection, $path, $isUrl = false)
    {
        if ($isUrl)
            return $model->addMediaFromUrl($path)
                ->toMediaCollection($collection);
        return $model->addMedia($path)
            ->preservingOriginal()
            ->toMediaCollection($collection);
    }
