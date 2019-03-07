<?php
namespace Yeti\Main\Exception;

use \Illuminate\Http\Request;

use \Illuminate\Support\Facades\URL;
use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Log;

use \Illuminate\Foundation\Exceptions\Handler;
use \Illuminate\Auth\AuthenticationException;
use \Illuminate\Auth\Access\AuthorizationException;
use \Illuminate\Validation\ValidationException;

use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpKernel\Exception\HttpException;

use \Yeti\Main\Exception\RetrievableException;
use \Yeti\Main\Exception\InvalidScopeException;

use \Throwable;
use \Exception;

class Interceptor extends Handler {

	/**
	 * @var array
	 */
	protected $dontReport = [
		HttpException::class,
		ValidationException::class,
		AuthorizationException::class,
		AuthenticationException::class,
		RetrievableException::class,
	];

	/**
	 * Report or log an exception.
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param Throwable $Exception $Exception
	 * @throws Exception
	 */
	public function report(Throwable $Exception) {
		parent::report($Exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  Request $Request
	 * @param  Throwable $Exception
	 * @return \Illuminate\Http\Response|array
	 */
	public function render($Request, Throwable $Exception) {
		if ($Request->isXmlHttpRequest()){
			if ($Exception instanceof RetrievableException
				&& !config('app.debug')){

					return response()->json(['error' => true,
						'message' => 'Something wrong!']);
			}

			return response()->json(['error' => true,
				'class' => get_class($Exception), 'message' => $Exception->getMessage()]);
		}

		if ($this->isHttpException($Exception) && $Exception->getStatusCode() == 404){
			return response()->view('404', [
				'message' => $Exception->getMessage()]);
		}

		if ($Exception instanceof InvalidScopeException){
			return redirect()->to('/');
		}

		if ($Exception instanceof RetrievableException){
			return redirect()->to(URL::previous())
				->withError($Exception->getMessage());
		}



		return parent::render($Request, $Exception);
	}
}
