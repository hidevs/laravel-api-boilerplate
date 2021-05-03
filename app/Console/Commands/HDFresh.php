<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class HDFresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:fresh';

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
        $currentDirectory = Jalalian::now()->format('Y');
        if ($this->confirm('Remove ' . $currentDirectory . ' media directory ?')) {
            Storage::disk('cdn')->deleteDirectory($currentDirectory);
            $this->comment('hd:fresh ==> Remove '. $currentDirectory . ' directory with all files');
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
