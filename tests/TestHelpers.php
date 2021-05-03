<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;


trait TestHelpers
{
    use WithFaker, DatabaseTransactions;

    public User $current_user;

    public function authorizationHeader(string $token = null): array
    {
        return [
            'Authorization' => 'Bearer ' . ($token ?? auth()->login($this->current_user))
        ];
    }

    public function set_random_user($acting = true)
    {
        $this->current_user = User::find($this->faker->numberBetween(1, User::count()));
        if ($acting) $this->actingAs($this->current_user);
    }

    public function random_model(string $model)
    {
        if ($model::count() < 1) return null;
        return $model::findOrFail($this->faker->numberBetween(1, $model::count()));
    }

    public function fake_model_data(string $model, array $attributes = [], bool $only = true): array
    {
        $result = $model::factory()->definition();
        if (!!count($attributes))
            return $only
                ? Arr::only($result, $attributes)
                : Arr::except($result, $attributes);
        return $result;
    }
}
