<?php
namespace Yeti\Main\Command;

use \Illuminate\Routing\Console\ControllerMakeCommand;

class LaravelControllerMake extends ControllerMakeCommand {

	/**
	 * Get the stub file for the generator.
	 * @return string
	 */
	protected function getStub() {
		return __DIR__ . '/stubs/controller.stub';
	}

	/**
	 * Get the default namespace for the class.
	 * @param  string $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace) {
		return $rootNamespace . '\Controller';
	}

}
