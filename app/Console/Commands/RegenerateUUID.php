<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\User;
use Ramsey\Uuid\Uuid;

class RegenerateUUID extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'integration:users:regenerate-uuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'regenarate uuids for all users';

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
    public function fire()
    {
        $users = User::all();
        foreach ($users as $user) {
            echo $user->id."\n";
            $user->qrcode = Uuid::uuid4();
            $user->save();
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
