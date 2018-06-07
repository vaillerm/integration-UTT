<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Newcomer;

/**
 * Import script for the newcomer's CSV file.
 *
 * The following format should be respected:
 *  0. État civil
 *  1. Nom
 *  2. Prénom
 *  3. Date de naissance
 *  4. Téléphone
 *  5. Email
 * (6. Niveau)
 */
class NewcomersImport extends Command
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
    protected $description = 'Imports a CSV file with all the newcomers in the database.';

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
        $file = $this->argument('file');
        if (! file_exists($file) || !is_file($file)) {
            $this->error('Please provide a valid file.');
        }
        if ($handle = fopen($file, 'r')) {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {

                // If the "level" column is present, use it instead of the
                // "level" option.
                $level = $this->option('level');
                if (count($data) === 7) {
                    $level = $data[6];
                } elseif ($this->option('level') === null) {
                    $this->error('The file does not include the level of the newcomer, please provide it via the "level" flag.');
                    return;
                }

                // See the class description for file format.
                $attributes = [
                    'first_name' => $data[2],
                    'last_name'  => $data[1],
                    'password'   => Str::random(6),
                    'sex'        => (strpos($data[0], 'M') !== false) ? 'M' : 'F',
                    'email'      => $data[5],
                    'phone'      => $data[4],
                    'level'      => $level,
                    'birth'      => new DateTime($data[3]),
                ];

                $newcomer = Newcomer::create($attributes);
                if ($newcomer->save() === false) {
                    $this->error('Error while adding ' . $newcomer->first_name . ' ' . $newcomer->last_name);
                }
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['file', InputArgument::REQUIRED, 'File to import.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['level', InputArgument::OPTIONAL, InputOption::VALUE_REQUIRED, 'If not provided, the last column will be used.'],
        ];
    }
}
