<?php
// php/eliminar_cuenta.php
header("Content-Type: application/json; charset=UTF-8");
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Sesión no iniciada."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || empty($input['password'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "La contraseña es requerida para verificar."]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['usuario_id']]);
    $user = $stmt->fetch();

    if ($user && password_verify($input['password'], $user['password'])) {
        $stmtDelete = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmtDelete->execute([':id' => $_SESSION['usuario_id']]);
        
        session_destroy();
        echo json_encode(["status" => "success", "message" => "Cuenta eliminada."]);
    } else {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Contraseña de verificación incorrecta."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error interno.", "debug" => $e->getMessage()]);
}
