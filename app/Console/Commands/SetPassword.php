<?php

namespace App\Console\Commands;

use App\Jobs\mailCron;
use Illuminate\Console\Command;
use Illuminate\Queue\Queue;
use App\Models\User;

/**
 * Let you set the password of any user
 */
class SetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'integration:user:set-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the password of an user.';

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
        $this->info('This command let you set the password of an user.');

        do {
            $login = $this->ask('What\'s his login?');
            $user = User::where('login', $login)->first();
            if (!$user) {
                $this->info('User not found, try again!');
            }
        } while (!$user);

        $this->info('Found: '. $user->fullName() . ' (' . $user->email . ')');
        $password = $this->secret('New password?');

        $user->setPassword($password);
        $user->save();

        $this->info('Done.');
    }
}
