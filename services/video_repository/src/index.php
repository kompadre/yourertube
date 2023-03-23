<?php
require_once __DIR__ . '/common/vendor/autoload.php';

$key = getenv('JWT_SECRET');
$auth = new \Auth\Auth(new \Auth\JwtAuthProvider($key));
$status = 'ok';
$error = '';
$result = ['status' => 'ok', 'error' => ''];
$user_id = $auth->retrieve()?->user_id ?? null;
$uploadAction = function() use ($user_id) {
    $user_id = preg_replace('/[^0-9a-z_]/', '', $user_id);
    $targetDir = '/media/uploaded/' . $user_id;
    if (!is_dir($targetDir)) {
        umask(0);
        mkdir($targetDir, 0777, true);
    }
    $filename = sha1_file($_FILES['file']['tmp_name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . '/' . $filename);
    $result['uploaded_filename'] = $user_id . '/' . $filename;
    return $result;
};

$result = match ($_SERVER['REQUEST_URI']) {
    $user_id === null => ['status' => 'ko', 'error' => 'not authorised'],
    '/repo/upload' => $uploadAction(),
    default => ['status' => 'ko', 'error' => 'not found'],
};
header('Content-type: application/json', true);
echo json_encode($result);