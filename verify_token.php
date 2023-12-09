<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Zezwalaj na żądania z Twojego frontendu
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Zezwalaj na konkretne metody HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Zezwalaj na konkretne nagłówki
header("Access-Control-Allow-Credentials: true"); // Jeśli używasz uwierzytelniania, np. cookies

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Zakończ skrypt dla żądań OPTIONS
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
    http_response_code(401); // Nieautoryzowany
    $response = ['success' => false, 'message' => 'Invalid token'];
}
