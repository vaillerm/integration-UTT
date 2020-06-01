<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\User;
use Symfony\Component\Filesystem\Filesystem;

class ImportStudentPictures extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'integration:students:importPictures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import students pictures.';

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
        $students_profile_picture_path = public_path().'/uploads/students-trombi/';
        $filesystem = new Filesystem();
        try {
            $filesystem->mkdir($students_profile_picture_path, 0777);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }

        $i = 0;
        $list = User::student()->get();
        foreach ($list as $student) {
            $i++;
            echo $i . "/" . $list->count() . " " . $student->fullName() . " " . "\n";
            $picture = @file_get_contents('https://etu.utt.fr/uploads/photos/' . $student->etuutt_login . '.png');
            if (empty($picture)) {
                $picture = @file_get_contents('https://etu.utt.fr/uploads/photos/' . $student->etuutt_login . '.jpg');
            }
            if (empty($picture)) {
                $picture = @file_get_contents('https://etu.utt.fr/uploads/photos/' . $student->etuutt_login . '_official.jpg');
            }
            if (empty($picture)) {
                echo "Error while trying to download student picture of ". $student->fullName() . " (" . $student->student_id . ")\n";
            }
            else {
                file_put_contents($students_profile_picture_path . $student->student_id . '.jpg', $picture);
            }
        }
        $this->info('Done!');
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
