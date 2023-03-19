<?php
require_once __DIR__ . '/../common/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'example_key';
$payload = [
    'iss' => 'http://' . $_SERVER['HTTP_HOST'],
    'iat' => time(),
    'expires_in' => 86400,
    'user_id' => 1357000000
];

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
$jwt = JWT::encode($payload, $key, 'HS256');
print_r($jwt);
$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

print_r($decoded);
