<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Newcomer;

class RenderNewcomers extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'newcomers:render';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Render newcomers';

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
        $newcomers = Newcomer::all();
        foreach ($newcomers as $newcomer) {
            echo 'php app/console  etu:users:create --login "adm' . $newcomer->id . '" --password="'.$newcomer->password.'" --firstName "'. addslashes($newcomer->first_name) .'" --lastName "' . addslashes($newcomer->last_name). '" --email "'.$newcomer->email.'" --branch "'.$newcomer->level.'"'."\n";
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
