<?php

namespace App\Traits;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponse
{
	/**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
	 * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
	protected function success(string $message = null, $data = null, string $token = null, int $code)
	{
		return response()->json([
			'success' => true,
			'message' => $message,
			'data' => $data,
			'token' => $token,
			'http_status_code' => $code
		]);
	}

	/**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
	 * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error(string $message = null, $data = null, string $token = null, int $code )
	{
		return response()->json([
			'success' => false,
			'message' => $message,
			'data' => $data,
			'token' => $token,
			'http_status_code' => $code
		]);
	}

}