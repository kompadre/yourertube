<?php

namespace Auth;


class Auth
{
	public function __construct(private IAuthProvider $authProvider) {}
	public function retrieve(): \stdClass|false {
		return $this->authProvider->retrieve();
	}
	
	public function store(array $payload, $ttl=null): void {
		$this->authProvider->store($payload, $ttl);
	}

    public function delete(): void {
        $this->authProvider->delete();
    }
}