<?php

namespace Yeti\Main\Command;

use \Illuminate\Console\Command;
use \Yeti\Main\Model\Project;

class YetiProjectConfig extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'yeti:project:config {name} {key} {value}';

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function handle() {
		try {
			$Project = Project::where('name', '=', $this->argument('name'))
				->first();

			if (is_null($Project)){
				throw new \Exception('Project "' . $this->argument('name') . '" is not exists!');
			}

			if (!preg_match('/^[A-Za-z][A-Za-z0-9_-]{2,}$/', $this->argument('key'))){
				throw new \Exception('Invalid key!');
			}

			$Project->config = array_merge($Project->config, [
				$this->argument('key') => $this->argument('value')]);

			$Project->save();

			$this->comment('Project "' . $Project->name . '" successfuly configured!');

		}catch (\Exception $Exception){
			$this->error($Exception->getMessage());
		}
	}

}
