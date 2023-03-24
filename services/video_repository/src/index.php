<?php
require_once __DIR__ . '/common/vendor/autoload.php';

$key = getenv('JWT_SECRET');
$auth = new \Auth\Auth(new \Auth\JwtAuthProvider($key));
$status = 'ok';
$error = '';
$result = ['status' => 'ok', 'error' => ''];
$user_id = $auth->retrieve()?->user_id ?? null;
if ($user_id !== null)
    $user_id = preg_replace('/[^0-9a-z_]/', '', $user_id);

$uploadAction = function() use ($user_id, $result) {
    $tmpname = $_FILES['file']['tmp_name'];
    $extension = match(mime_content_type($tmpname)) {
        'image/jpeg', 'image/jpg' => 'jpg',
        'image/png'     => 'png',
        'image/gif'     => 'gif',
        default         => null,
    };

    if ($extension === null) {
        return ['status' => 'ko', 'error' => 'invalid file'];
    }
    $targetDir = '/media/uploaded/' . $user_id;
    if (!is_dir($targetDir)) {
        umask(0);
        mkdir($targetDir, 0777, true);
    }
    $filename = sha1_file($_FILES['file']['tmp_name']) . '.' . $extension;
    move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . '/' . $filename);
    $result['uploaded_filename'] = $user_id . '/' . $filename;
    return $result;
};
$galleryAction = function() use ($user_id, $result) {
    $targetDir = '/media/uploaded/' . $user_id;
    $images = glob($targetDir . '/*');
    $result['images'] = $images;
    return $result;
};

$result = match ($_SERVER['REQUEST_URI']) {
    empty($user_id) => ['status' => 'ko', 'error' => 'not authorised'],
    '/repo/upload' => $uploadAction(),
    '/repo/gallery' => $galleryAction(),
    default => ['status' => 'ko', 'error' => 'not found'],
};
header('Content-type: application/json', true);
echo json_encode($result);