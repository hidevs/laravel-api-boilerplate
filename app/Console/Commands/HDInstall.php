<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class HDInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Full fresh files and migrations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->callSilently('optimize');

        if (empty(env('APP_KEY')) && $this->confirm('Generate APP secret key ?')) {
            $this->call('key:generate');
        }

        if (empty(env('JWT_SECRET')) && $this->confirm('Generate JWT secret key ?')) {
            $this->call('jwt:secret');
        }

        if (!file_exists(public_path('cdn')) && $this->confirm('Link storages ?')) {
            $this->call('storage:link');
        }

        if ($this->confirm('Remove media directory ?')) {
            Storage::disk('cdn')->deleteDirectory('media');
            $this->comment('site:fresh ==> Remove media directory with all files');
        }

        if ($this->confirm('Fresh migration ?')) {
            $this->call('migrate:fresh');
            $this->comment('hd:fresh ==> Complete Fresh');
        }

        if ($this->confirm('Seeding to database ?')) {
            try {
                $this->call('db:seed');
            } catch (\Exception $e) {
                $this->error('hd:fresh ==> Seeding have a error: ' . $e->getMessage());
            }
            $this->comment('hd:fresh ==> Complete Seeding');
            $this->callSilently('optimize');


            if ($this->confirm('Start queue for conversion media ?')) {
                $this->comment('hd:fresh ==> Starting Queue for conversion media');
                $this->call('queue:work', [
                    '--tries' => 1
                ]);
                $this->callSilently('optimize');
            }
        }
        
        return 0;
    }
}
