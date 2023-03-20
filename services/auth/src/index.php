<?php
require_once __DIR__ . '/../common/vendor/autoload.php';
use Firebase\JWT\JWT;

$key = getenv('JWT_SECRET');
$payload = [
    'iss' => 'http://' . $_SERVER['HTTP_HOST'],
    'iat' => time(),
    'exp' => time()+86400,
    'user_id' => 1
];

$jwt = JWT::encode($payload, $key, 'HS256');
setcookie('token', $jwt, time()+86400, '/', $_SERVER['HTTP_HOST'], true);
header('Location: /user/');