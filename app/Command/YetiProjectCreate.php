<?php

namespace Yeti\Main\Command;

use \Illuminate\Console\Command;
use \Yeti\Main\Model\Project;

class YetiProjectCreate extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'yeti:project:create {name} {domain}';

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function handle() {
		try {
			$Project = new Project();

			if (!preg_match('/^[A-Za-z][A-Za-z0-9_-]{2,}$/', $this->argument('name'))){
				throw new \Exception('Invalid name!');
			}

			if (Project::where('name', '=', $this->argument('name'))->count() > 0){
				throw new \Exception('Project already exists!');
			}

			$Project->name = trim(strtolower($this->argument('name')));

			if (!filter_var($this->argument('url'), FILTER_VALIDATE_DOMAIN)){
				throw new \Exception('Invalid domain!');
			}

			$Project->domain = strtolower($this->argument('domain'));
			$Project->save();

			$this->comment('Project "' . $Project->name . '" successfuly created!');

		}catch (\Exception $Exception){
			$this->error($Exception->getMessage());
		}
	}

}
