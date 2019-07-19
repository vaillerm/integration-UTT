<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class refreshNewcomers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
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
                Log::error('UTT Admitted api error: '. $admittedList->error->text);
            }
            else {
                Log::error('UTT Admitted api error: '. $response->getBody()->getContents());
            }
            return;
        }

        // Add and update users
        $i = 1;
        foreach ($admittedList as $admitted) {

            // Filter unsuported formations
            if (! in_array($admitted->DIPLOME_C, ['ING2', 'MST'])) {
                Log::error($i.'/'.count($admittedList). ': ERROR : '
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

            /**
            // Debug
            if($this->getOutput()->isVerbose()) {
                $this->info($i.'/'.count($admittedList). ': ' . $action . ' : '
                    . $admitted->PRENOM . ' ' . $admitted->NOM . '('
                    . $admitted->ADM_ID . ')');
            }
             * **/
            $i++;
        }
    }
}
