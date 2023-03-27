<?php
require_once __DIR__ . '/common/vendor/autoload.php';

$key = getenv('JWT_SECRET');
$auth = new \Auth\Auth(new \Auth\JwtAuthProvider($key));
$status = 'ok';
$error = '';
$result = ['status' => 'ok', 'error' => ''];

$loginAction = function () use ($auth, $result) {
    $post = json_decode(file_get_contents('php://input'), true);
    if (empty($post)) {
        return ['status' => 'ko', 'error' => 'wrong input'];
    }

    if (!isset($post['user']) || !isset($post['pass']) || !($post['user'] == 'admin' && $post['pass'] == 'admin')) {
        $result['error'] = 'wrong credentials';
        $result['status'] = 'ko';
    } else {
        $auth->store(['user_id' => 'admin']);
    }
    return $result;
};

$logoutAction = function() use ($auth, $result) {
    $auth->delete();
    return $result;
};

$checkAction = function() use ($auth, $result) {
    $result['result'] = $auth->retrieve();
    return $result;
};

$result = match ($_SERVER['REQUEST_URI']) {
    '/auth/login' => $loginAction(),
    '/auth/logout' => $logoutAction(),
    '/auth/check' => $checkAction(),
    default => ['status' => 'ko', 'error' => 'not found'],
};

header('Content-type: application/json', true);
if (isset($result['status_code'])) {
	http_response_code($result['status_code']);
	unset($result['status_code']);
}
echo json_encode($result);
exit();