<?php

namespace App\Console\Commands;

use App\Jobs\mailCron;
use Illuminate\Console\Command;
use Illuminate\Queue\Queue;

class CreateFirstCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:cron:first';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permet de lancer la premiere commande cron';

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
        $job = (new mailCron())
            ->onQueue('high');
        dispatch($job);
        $this->line('Commande ajouté !');
    }
}
