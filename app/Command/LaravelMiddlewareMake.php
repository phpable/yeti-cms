<?php
namespace Yeti\Main\Command;

use \Illuminate\Routing\Console\MiddlewareMakeCommand;

class LaravelMiddlewareMake extends MiddlewareMakeCommand {

	/**
	 * Get the stub file for the generator.
	 * @return string
	 */
	protected function getStub() {
		return __DIR__ . '/stubs/middleware.stub';
	}

	/**
	 * Get the default namespace for the class.
	 * @param  string $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace) {
		return $rootNamespace . '\Middleware';
	}

}
