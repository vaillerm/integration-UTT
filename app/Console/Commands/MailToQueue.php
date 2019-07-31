<?php

namespace App\Console\Commands;

use App\Jobs\generateMailBatch;
use Illuminate\Console\Command;

class MailToQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:mails:to-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met dans la queue les emails dont la date d\'envoi programmé vient de passer';

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
        dispatch(new generateMailBatch());
        $this->line('Tache programmé');
    }
}
