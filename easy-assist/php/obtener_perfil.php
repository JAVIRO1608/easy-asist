<?php
// php/obtener_perfil.php
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(["status" => "error", "message" => "No autorizado."]);
    exit;
}

try {
    // Consulta a la tabla original que tenías antes del cambio
    $stmt = $pdo->prepare("SELECT accion, fecha FROM historial_acciones WHERE usuario_id = :user_id ORDER BY fecha DESC");
    $stmt->execute([':user_id' => $_SESSION['usuario_id']]);
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json; charset=UTF-8');
    // IMPORTANTE: Mantener la clave "registros" para no romper el HTML viejo
    echo json_encode([
        "status" => "success",
        "usuario" => [
            "name" => $_SESSION['usuario_name'],
            "email" => $_SESSION['usuario_email']
        ],
        "registros" => $registros ? $registros : []
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
}
