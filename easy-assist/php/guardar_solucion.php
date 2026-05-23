<?php
// php/guardar_solucion.php
require_once 'conexion.php';

// Validar que el usuario tenga la sesión activa
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Sesión no iniciada."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$problema = trim($input['problema'] ?? '');
$solucion = trim($input['solucion'] ?? '');

if (empty($problema) || empty($solucion)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO soluciones_registradas (usuario_id, problema, solucion_aportada) VALUES (:user_id, :prob, :sol)");
    $stmt->execute([
        ':user_id' => $_SESSION['usuario_id'],
        ':prob'    => $problema,
        ':sol'     => $solucion
    ]);

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(["status" => "success", "message" => "Solución registrada correctamente."]);
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(["status" => "error", "message" => "Error al guardar.", "debug" => $e->getMessage()]);
}
