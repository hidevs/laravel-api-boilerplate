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
                $this->addMedia($model, 'avatar', $simpleImage);
                break;
            default:
                break;
        }

        return [];
    }

    public function addMedia($model, string $collection, $file)
    {
        if (!is_null($this->owner))
            return auth_resolver($this->owner, function () use ($model, $file, $collection) {
                return $model->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection($collection);
            });
        return $model->addMedia($file)
            ->preservingOriginal()
            ->toMediaCollection($collection);
    }
}
