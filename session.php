<?php
require_once 'db_config.php';
require_once './vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Session
{
    private $db;
    private $key;

    public function __construct($db)
    {
        $this->db = $db;
        $this->key = "2S5JuxOxYpPzVpSVP-utovOHaFNTmJT0Viqfx8H03fg";
    }

    public function createJwt($userId)
    {
        $payload = [
            "user_id" => $userId,
        ];
        $alg = 'HS256';
        return JWT::encode($payload, $this->key, $alg);
    }


    public function verifyJwt($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            error_log("Token wygasł: " . $e->getMessage());
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            error_log("Nieprawidłowa sygnatura: " . $e->getMessage());
        } catch (\Exception $e) {
            error_log("Inny błąd JWT: " . $e->getMessage());
        }
        return null;
    }
}
