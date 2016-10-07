<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Config;
use DB;

class APIBaseController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $error;

    /**
	 *	Constructor
	 */
	public function __construct() {
		$this->errors = array();

		DB::enableQueryLog();
	}

	/**
	 *	Walk recursive and runs the specific method
	 *  @param 	$obj 		object to walk
	 *	@param 	$closure 	function to be called
	 */
	private function walk_recursive(&$obj, $closure) {
		foreach($obj as $key => &$value) {
			if(is_object($value) || is_array($value)) {
				$this->walk_recursive($value, $closure);
			}
			else {
				$closure($value);
			}
		}		
	}
	
	public function error($validator, $code = 400) {
		
		$response = array();

		if(Config::get('app.debug')) {
			$queries = DB::getQueryLog();
			$response['queries'] = $queries;
		}

		if($validator) {
			$this->setValidationError($validator);
		}
		
		$json = json_decode(json_encode($this->errors));
		
		// $this->walk_recursive($json, function(&$value) {
		// 	if(is_null($value)) {
		// 		$value = "";
		// 	}
		// });

		$response['errors'] = $json;
		
		return $this->response($response, $code);
	}
	
	private function setValidationError($validator) {
		$this->errors = $validator->messages()->getMessages();
	}

	public function response($json = null, $status = 200) {
		if($json === null) {
			$json = new \stdClass;
		}
		return response()
					->json($json, $status);
	}

	public function setError($errors) {
		$this->errors = $errors;
		return $this;
	}
}
