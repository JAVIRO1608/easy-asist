<?php
// php/conexion.php

// 1. Forzar visualización extrema de errores antes de arrancar nada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Control estricto de sesiones locales
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// 3. Cabeceras CORS limpias sin variables dinámicas que puedan fallar
header("Access-Control-Allow-Origin: https://192.108.1.10");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// 4. Parámetros de red sin espacios ocultos (Rango 192.108)
$db_host = "192.108.1.11";
$db_port = "5432";
$db_name = "easyassist_db";
$db_user = "postgres";
$db_pass = "pirineus";

try {
    $dsn = "pgsql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name . ";";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        "status" => "error",
        "message" => "Error de conexión SQL",
        "debug" => $e->getMessage()
    ]);
    exit;
}
