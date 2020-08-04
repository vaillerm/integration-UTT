<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

/**
 * Let you set an user as admin
 */
class SetAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'integration:user:set-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set an user as admin.';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('This command let you set an user as an admin of the website.');

        do {
            $login = $this->ask('What\'s his login?');
            // Look for integration-utt login
            $user = User::where('login', $login)->first();
            if (!$user) {
                // Look for etuutt login
                $user = User::where('etuutt_login', $login)->first();
            }

            if (!$user) {
                $this->info('User not found, try again!');
            }
        } while (!$user);

        $this->info('Found: ' . $user->fullName() . ' (' . $user->email . ')');
        $confirm = $this->confirm('Do you really want to make this user admin?');
        if (!$confirm) {
            return$this->info('Cancelled.');
        }
        $user->admin = 100;
        $user->save();

        $this->info('Done.');
    }
}
