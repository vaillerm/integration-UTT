<?php
namespace App\Console\Commands;

use App\Jobs\refreshNewcomers;
use Illuminate\Console\Command;

/**
 * Import newcomers from UTT API
 */
class ImportNewcomers extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'integration:newcomers:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports newcomers from UTT API.';

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
        dispatch(new refreshNewcomers());
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
