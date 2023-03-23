<?php

namespace Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

/**
 * Provides a mechanism to store authentication. 
 */
class JwtAuthProvider implements IAuthProvider
{
	const JWT_ALGO = 'HS256';
	const COOKIE_INDEX = 'JWT_COOKIE'; 
	const DEFAULT_TTL = 3600;

	public function __construct(private string $key) {}

	/**
	 * We authenticate based on $token returning JWT payload as a stdClass 
	 * or fail so returning a false.  
	 * 
	 * @param string $token
	 * @param $index
	 * @return stdClass|false
	 */
	public function retrieve(): stdClass|false
	{
		$token = $_COOKIE[self::COOKIE_INDEX] ?? null;
		if (!$token) return false;
		$payload = JWT::decode($token, new Key($this->key, self::JWT_ALGO));
		return $payload ?? false;
	}

	/**
	 * @param array $payload
	 * @param $ttl
	 * @return void
	 */
	public function store(array $payload, ?int $ttl): void
	{
		if (!$ttl) $ttl = self::DEFAULT_TTL;
		$jwt = JWT::encode($payload, $this->key, self::JWT_ALGO);
		setcookie(self::COOKIE_INDEX, $jwt, time()+$ttl, '/', $_SERVER['HTTP_HOST'], true);
	}

    public function delete(): void
    {
        unset($_COOKIE['remember_user']);
        setcookie(self::COOKIE_INDEX, '', -1, '/', $_SERVER['HTTP_HOST'], true);
    }
}