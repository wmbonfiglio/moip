<?php namespace SOSTheBlack\Moip\Commands;

use Moip;
use Illuminate\Console\Command;

class MoipFinancingCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'moip:financing';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$moip = Moip::first();
		$moip->financing = $this->confirm('Finacic activated? [yes|no]');
		$moip->save();
	}
}
