<?php
namespace App\Services;
use Cache;
use GuzzleHttp\Client;

class GetPostToken {

	protected $http;
	public function __construct(Client $http) {
		$this->http = $http;
	}

	public static function get() {
		if (Cache::has('client_credentials_token')) {
			return Cache::get('client_credentials_token');
		}
		$response = $this->http->get(env('GET_TOKEN_URL'));
		$token = json_decode($response->getBody(), true)['access_token'];
		Cache::forever('client_credentials_token', $token);
		return $token;
	}
}