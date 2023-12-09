<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

require_once 'db_config.php';
require_once 'session.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($username) && !empty($password)) {
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $session = new Session($conn);
        $jwt = $session->createJwt($user['id']);

        $userData = [
            'id' => $user['id'],
            'username' => $user['username']
        ];

        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "user" => $userData,
            "token" => $jwt
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Username and password required"]);
}

$conn->close();
