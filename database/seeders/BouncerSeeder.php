<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Silber\Bouncer\Bouncer;

class BouncerSeeder extends Seeder
{
    /**
     * @var Bouncer
     */
    public $bouncer;

    public function __construct(Bouncer $bouncer)
    {
        $this->bouncer = $bouncer;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->registerRoles();

        $this->registerPermissions();
    }

    public function registerPermissions()
    {
        $this->bouncer->allow(User::ROLE_SUPER_ADMIN)->everything();

        $this->bouncer->allow(User::ROLE_USER)->toOwn(User::class)->to([
            'update', 'show'
        ]);


    }

    public function registerRoles()
    {
        foreach (User::ROLES as $role)
            $this->bouncer->role()->findOrCreateRoles(['name' => $role]);
    }
}
