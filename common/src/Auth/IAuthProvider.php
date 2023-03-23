<?php

namespace Auth;
interface IAuthProvider
{
	public function retrieve(): \stdClass|false;
	public function store(array $payload, ?int $ttl): void;
    public function delete(): void;
	
}
