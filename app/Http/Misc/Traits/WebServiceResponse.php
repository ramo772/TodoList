<?php

namespace App\Http\Misc\Traits;

trait WebServiceResponse
{
	public function success_response($response)
    {
    	return $this->general_response($response, "", 200);
    }

    public function dummy_response($message)
    {
    	return $this->general_response(['message' => $message], "", 200);
    }

    public function error_response($error, $code = 422)
    {
    	return $this->general_response("", $error, $code);
    }

    private function general_response($data = "", $error = "", $status_code = 200)
    {
    	return response()->json([
    		"data"          => $data,
    		"error"         => $error,
    		"status_code"   => $status_code
    	],$status_code);
    }

}