<?php
namespace Yeti\Main\Command;

use \Illuminate\Console\Command;
use \Yeti\Main\Model\User;

class YetiAdminList extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'yeti:admin:list';

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function handle() {
		try {

			$count = 0;
			foreach (User::all() as $User){
				$this->info(sprintf('#%1$04d', $User->id));
				$this->info($User->name);
				$this->info($User->email);
				echo "\n";

				$count++;
			}

			echo "\n";
			$this->info("Total: " . $count);
		}catch (\Exception $Exception){
			$this->error($Exception->getMessage());
		}
	}
}
