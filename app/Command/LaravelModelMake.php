<?php
namespace Yeti\Main\Command;

use \Illuminate\Foundation\Console\ModelMakeCommand;

class LaravelModelMake extends ModelMakeCommand {

	/**
	 * Get the default namespace for the class.
	 * @param  string $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace) {
		return $rootNamespace . '\Model';
	}

}
