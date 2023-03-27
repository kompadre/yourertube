<?php
require_once __DIR__ . '/common/vendor/autoload.php';

$key = getenv('JWT_SECRET');
$auth = new \Auth\Auth(new \Auth\JwtAuthProvider($key));
$status = 'ok';
$error = '';
$result = ['status' => 'ok', 'error' => ''];
$user_id = $auth->retrieve()?->user_id ?? null;
$uri = $_SERVER['REQUEST_URI'];
if ($user_id)
	$user_id = preg_replace('/[^0-9a-z_]/', '', $user_id);
if (!$user_id)
	$uri = 'error:forbidden';

$uploadAction = function() use ($user_id, $result) {
	$tmpname = $_FILES['file']['tmp_name'];
	$mime = mime_content_type($tmpname);
	$extension = match($mime) {
		'image/jpeg', 'image/jpg' => 'jpg',
		'image/png'     => 'png',
		'image/gif'     => 'gif',
		'video/x-msvideo' => 'avi',
		'video/mp4'     => 'mp4',
		default         => null,
	};

	if ($extension === null) {
		return ['status' => 'ko', 'error' => 'invalid file: ' . $mime];
	}
	$isVideo = $extension == 'avi' || $extension == 'mp4';
	$targetDir = '/media/uploaded/' . $user_id;
	if (!is_dir($targetDir)) {
		umask(0);
		mkdir($targetDir, 0777, true);
	}
	$filename = sha1_file($_FILES['file']['tmp_name']) . '.' . $extension;
	$targetFile = $targetDir . '/' . $filename;
	move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
	$result['uploaded_filename'] = $user_id . '/' . $filename;
	$result['is_video'] = $isVideo;
	$q = new \Queue\Queue();
	$q->publishTask(['filename' => $targetFile, 'action' => 'process']);
	return $result;
};
$galleryAction = function() use ($user_id, $result) {
	$targetDir = '/media/uploaded/' . $user_id;
	$images = []; $videos = [];
	foreach(glob($targetDir . '/*') as $file) {
		if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['mp4', 'avi'])) {
			$video = ['path' => $file];
			$video['thumb'] = file_exists($file . '.thumb.png') ? $file . '.thumb.png' : '/app/processing.thumb.png';
			$videos[] = $video;
		}
		else if (!str_ends_with($file, "thumb.png"))
			$images[] = $file;
	}
	$result['images'] = $images;
	$result['videos'] = $videos;
	return $result;
};
$deleteAction = function() use ($user_id, $result) {
	$input = json_decode(file_get_contents('php://input'), true);
	$path = $input['filename'] ?? null;
	if (!$path) return ['status' => 'ko', 'error' => 'file not found'];
	$filename =  basename($path);
	$constrainedpath =  '/media/uploaded/' . $user_id . '/' . $filename;
	if (!file_exists($constrainedpath)) return ['status' => 'ko', 'error' => 'file not found', 'file' => $constrainedpath];
	unlink( $constrainedpath );
	if (file_exists($constrainedpath . '.thumb.png'))
		unlink( $constrainedpath . '.thumb.png' );

	$result['removed_file'] = $constrainedpath;
	return $result;
};

$result = match ($uri) {
	'/repo/upload' => $uploadAction(),
	'/repo/gallery' => $galleryAction(),
	'/repo/delete' => $deleteAction(),
	'/repo/info' => (function() { phpinfo(); die(); })(),
	'error:forbidden' => ['status' => 'ko', 'error' => 'forbidden', 'status_code' => 403],
	default => ['status' => 'ko', 'error' => 'not found', 'status_code' => 404],
};

header('Content-type: application/json', true);
if (isset($result['status_code'])) {
	http_response_code($result['status_code']);
	unset($result['status_code']);
}
echo json_encode($result);
exit();