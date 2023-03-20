<?php

namespace Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuthProvider implements IAuthProvider
{
	public function __construct(private $key) { }
	const JWT_ALGO = 'HS256';
	public function validate(string $token, $index='user_id'): string|false
	{
		$jwt = JWT::decode($token, new Key($this->key, self::JWT_ALGO));
		return $jwt['user_id'] ?? false;
	}
}