<?php
if (!isset($_COOKIE['token'])) die('No session! Please <a href="/auth/">authorize</a>.');

require_once __DIR__ . '/../common/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = getenv('JWT_SECRET');
$payload = $_COOKIE['token'];
try {
	$jwt = JWT::decode($payload, new Key($key, 'HS256'));	
} catch (\Exception $e) {
	echo 'Wrong session! Please <a href="/auth/">authorize</a>.';
	exit();
}
?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<body>
<h1>Upload your video: </h1>
<form action="" method="post" enctype="multipart/form-data">
	Video: <input type="file" name="video" /><input type="submit" />
</form>
</body>
</html>
