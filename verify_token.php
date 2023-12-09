<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
require_once 'db_config.php';
require_once 'session.php';

$authHeader = getallheaders()['Authorization'] ?? '';
$jwtToken = str_replace('Bearer ', '', $authHeader);

$session = new Session($conn);
$decodedToken = $session->verifyJwt($jwtToken);

if ($decodedToken) {


    $response = ['success' => true, 'message' => 'Token is valid', 'user' => $decodedToken];
} else {
    http_response_code(401);
    $response = ['success' => false, 'message' => 'Invalid token'];
}
