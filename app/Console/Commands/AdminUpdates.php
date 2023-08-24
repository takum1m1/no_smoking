<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AdminUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:Admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Admins';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $adminId = $this->ask('Please enter the user id');

        $admin = User::findOrfail($adminId);

        if ($admin->is_admin === 1) {
            $this->error('This user is already an admin.');
            return Command::FAILURE;
        }

        $admin->is_admin = 1;
        $admin->saveOrFail();

        $this->info('The user has been updated to admin.');
        return Command::SUCCESS;
    }
}
