<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->auth();

        $this->models();
    }

    public function auth()
    {
        settings()->group('auth')->set([
            // set all authentication setting here
        ]);
    }

    public function models()
    {
        settings()->group(model_setting_name(User::class))->set([
            'small_avatar_width' => 64,
            'small_avatar_height' => 64,
            'medium_avatar_width' => 128,
            'medium_avatar_height' => 128,
            'large_avatar_width' => 512,
            'large_avatar_height' => 512,
        ]);
    }
}
