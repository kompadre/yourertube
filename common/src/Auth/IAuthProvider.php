<?php

namespace Auth;
interface IAuthProvider
{
	public function validate(string $token): string|false;
}
