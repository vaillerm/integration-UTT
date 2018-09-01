<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\User;
use Config;

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
        $weiStart = new \Datetime(Config::get('services.wei.start'));
        $client = new \GuzzleHttp\Client([
            'base_uri' => Config::get('services.admitted_api.baseuri'),
        ]);

        // Query and decoding
        $response = $client->get(Config::get('services.admitted_api.basepath'). '/admis/'.$weiStart->format('Y,md'));
        $admittedList = json_decode($response->getBody()->getContents());
        if (count($admittedList) < 2 || (is_object($admittedList) && property_exists($admittedList, 'error'))) {
            if (is_object($admittedList) && property_exists($admittedList, 'error') && property_exists($admittedList->error, 'text')) {
                $this->error('UTT Admitted api error: '. $admittedList->error->text);
            }
            else {
                $this->error('UTT Admitted api error: '. $response->getBody()->getContents());
            }
            return;
        }

        // Add and update users
        $i = 1;
        foreach ($admittedList as $admitted) {

            // Filter unsuported formations
            if (! in_array($admitted->DIPLOME_C, ['ING2', 'MST'])) {
                $this->error($i.'/'.count($admittedList). ': ERROR : '
                . $admitted->PRENOM . ' ' . $admitted->NOM . '('
                . $admitted->ADM_ID . '): Cannot import unknown formation : '
                . $admitted->DIPLOME_C . ' | ' . $admitted->DIPL_SPEC_ABR);
                continue;
            }

            // Convert api data for our model
            $updatedData = [
                'admitted_id'           => $admitted->ADM_ID,
                'student_id'            => $admitted->ETU_ID,
                'last_name'             => $admitted->NOM,
                'first_name'            => $admitted->PRENOM,
                'postal_code'           => $admitted->CP,
                'country'               => $admitted->PAYS,
                'is_newcomer'           => true,
                'registration_email'    => $admitted->CPT_EMAIL,
                'registration_phone'    => $admitted->TEL,
                'branch'                => $admitted->DIPL_SPEC_ABR,
                'wei_majority'          => ($admitted->MAJEUR == 'true'),
            ];

            // Create or update
            $user = User::where('admitted_id', $admitted->ADM_ID);
            if ($admitted->ETU_ID) {
                $user = $user->orWhere('student_id', $admitted->ETU_ID);
            }
            $user = $user->first();

            $action = '';
            if (!$user || !$user->nosync) {
                if($user) {
                    $user->fill($updatedData);
                    $action = 'Updated';

                    //Si utilise un vieux truc de merde alors on update #API_CEDRE
                    if (($user->getPasswordType())['algoName'] == "unknown")
                        $user->password = $admitted->PASSWD;
                }
                else {
                    $user = new User($updatedData);
                    $action = 'Inserted';
                    $user->password = $admitted->PASSWD;
                }
                $user->login = $admitted->LOGIN;
                $user->save();
            }
            else {
                $action = 'Sync disabled';
            }

            // Debug
            if($this->getOutput()->isVerbose()) {
                $this->info($i.'/'.count($admittedList). ': ' . $action . ' : '
                . $admitted->PRENOM . ' ' . $admitted->NOM . '('
                . $admitted->ADM_ID . ')');
            }
            $i++;
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
