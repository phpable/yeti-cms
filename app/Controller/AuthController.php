<?php
namespace Yeti\Main\Controller;

use \Yeti\Main\Model\User;
use \Validator;
use \Yeti\Main\Controller\Abstracts\AController;

use \Illuminate\Foundation\Auth\ThrottlesLogins;
use \Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends AController {
	use AuthenticatesAndRegistersUsers;

	/**
	 * @var string
	 */
	protected $redirectPath = '/';

	/**
	 * @var string
	 */
	protected $loginPath = '/auth/login';

	/**
	 * Create a new authentication controller instance.
	 */
	public function __construct() {
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Get a validator for an incoming registration request.
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data) {
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 * @param  array $data
	 * @return User
	 */
	protected function create(array $data) {
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);
	}


}
