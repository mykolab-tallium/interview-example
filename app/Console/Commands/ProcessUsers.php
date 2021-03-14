<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // users table has about 500000 records.
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            echo $user->first_name;
            // Perform some action on user
        }
    }
}
