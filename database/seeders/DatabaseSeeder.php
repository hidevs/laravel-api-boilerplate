<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->mainData();

        $this->fakeData();
    }

    /**
     * Basically data must be seed at first initialize project
     */
    public function mainData()
    {
        $this->call(BouncerSeeder::class);
        $this->call(SettingSeeder::class);
    }

    /**
     * Fake data only for test
     */
    public function fakeData()
    {
        User::factory(10)->create();
        Media::factory(User::count())->create();
    }
}
