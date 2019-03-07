<?php
namespace Yeti\Main\Command;

use \Illuminate\Console\Command;
use \Yeti\Main\Model\User;

use \Exception;

class YetiAdminChange extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'yeti:admin:change {email} {newemail} {password} {confirm}';

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function handle() {
		try {
			if (!filter_var($this->argument('email'), FILTER_VALIDATE_EMAIL)){
				throw new Exception(printf('Invalid email: %s!', $this->argument('email')));
			}

			if (is_null($User = User::where('email', '=', $this->argument('email'))->first())) {
				throw new Exception('User is not exists!');
			}

			if (!empty($this->argument('newemail'))
				&& $this->argument('newemail') != '*') {

					if (!filter_var($this->argument('newemail'), FILTER_VALIDATE_EMAIL)){
						throw new Exception(printf('Invalid email: %s!', $this->argument('newemail')));
					}

					$User->email = trim(strtolower($this->argument('newemail')));
			}

			if (!empty($this->argument('password'))
				&& $this->argument('password') != '*') {

				if ($this->argument('password') !== $this->argument('confirm')){
					throw new Exception('Passwords do not match!');
				}

				if (!preg_match('/^[A-Za-z0-9_!@#$%-]+$/', $this->argument('password'))) {
					throw new Exception('Invalid password characters!');
				}

				$User->password = bcrypt($this->argument('password'));
			}

			$User->save();

			$this->info(sprintf('User %s have been successfully updated!', $User->email));
		}catch (Exception $Exception){
			$this->error($Exception->getMessage());
		}
	}
}
