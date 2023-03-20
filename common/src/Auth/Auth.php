<?php

namespace Auth;


class Auth
{
	public function __construct(private IAuthProvider $authProvider) {}
	public function validate(string $token): string|false {
		return $this->authProvider->validate($token);
	}
}