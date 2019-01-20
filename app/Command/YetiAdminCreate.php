<?php
namespace Yeti\Main\Command;

use \Illuminate\Console\Command;
use \Yeti\Main\Model\User;

class YetiAdminCreate extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'yeti:admin:create {email} {password} {confirm}';

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function handle() {
		try {
			$User = new User();

			if (!filter_var($this->argument('email'), FILTER_VALIDATE_EMAIL)){
				throw new \Exception('Invalid email!');
			}

			if (User::where('email', '=', $this->argument('email'))->count() > 0) {
				throw new \Exception('User already exists!');
			}

			$User->email = trim(strtolower($this->argument('email')));

			if (!preg_match('/^[A-Za-z0-9_!@#$%-]+$/', $this->argument('password'))){
				throw new \Exception('Invalid password characters!');
			}
			if ($this->argument('password') !== $this->argument('confirm')){
				throw new \Exception('Passwords not match!');
			}
			$User->password = bcrypt($this->argument('password'));

			$User->name = 'Crazy Yeti';
			$User->save();
			$this->comment('User "' . $User->email . '" have been successfully created!');

		}catch (\Exception $Exception){
			$this->error($Exception->getMessage());
		}
	}
}
