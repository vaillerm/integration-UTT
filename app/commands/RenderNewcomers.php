<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RenderNewcomers extends Command {

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
		$newcomers = Newcomer::where('level', 'TC')->get();
		// $newcomers = Newcomer::where('level', '!=', 'TC')->where('level', '!=', 'MMAPP')->get();
		foreach ($newcomers as $newcomer)
		{
			echo system('/usr/local/bin/wkhtmltopdf -B 0 -L 0 -R 0 -T 0 -O "Landscape" -s "A4" http://integration.utt.fr/dashboard/newcomers/' . $newcomer->id . ' ~/Workspace/parrainages/public/render/' . escapeshellarg($newcomer->last_name . ' ' . $newcomer->first_name) .'.pdf');
			$this->info('Done:' . $newcomer->first_name . ' ' . $newcomer->last_name);
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
