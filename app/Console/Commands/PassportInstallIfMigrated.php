<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Let you set the password of any user
 */
class PassportInstallIfMigrated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'passport:install-if-migrated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run passport install only if migration has already been done to avoid failure.';

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
        // Check if we can connect to the db
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return $this->error("Warning: Cannot run `php artisan passport:install` yet because database cannot be reached. Please run this command once database configured and migrated.\n".$e->getMessage());
        }

        // Check if the migration table exists
        if (!Schema::hasTable('migrations')) {
            return $this->error('Warning: Cannot run `php artisan passport:install` yet because you need to migrate the database first. Please run this command once database configured and migrated.');
        }

        Artisan::call('passport:install');
    }
}
