<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Newcomer;
use App\Models\Team;

class AttachTeam extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'newcomers:assignTeam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a team to all the newcomers without.';

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
        $newcomers = Newcomer::where('team_id', null)->orderByRaw(' RAND()')->get();
        if ($newcomers->count() == 0) {
            die($this->info('All the newcomers have a team.'));
        }

        $teams = Team::all();
        $count = floor($newcomers->count() / $teams->count());

        foreach ($teams as $team) {
            for ($i = 0; $i < $count; $i++) {
                $newcomer = $newcomers->pop();
                $newcomer->team_id = $team->id;
                if (! $newcomer->save()) {
                    die($this->error('Unable to add #' . $newcomer->id . ' to a team.'));
                }
            }
        }
        $this->info('Newcomers were added to their respective teams!');
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
