<?php
// php/navegacion.php
header("Content-Type: application/json; charset=UTF-8");
require_once 'conexion.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['pagina_actual_id']) || empty($input['boton'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Datos de navegación incompletos."]);
    exit;
}

$actual_id = intval($input['pagina_actual_id']);
$boton     = trim($input['boton']);

try {
    $stmt = $pdo->prepare("
        SELECT t.pagina_destino_id, p.ruta 
        FROM transiciones t
        JOIN paginas p ON t.pagina_destino_id = p.id
        WHERE t.pagina_actual_id = :actual_id AND t.boton = :boton AND t.activo = TRUE
    ");
    $stmt->execute([':actual_id' => $actual_id, ':boton' => $boton]);
    $transicion = $stmt->fetch();

    if ($transicion) {
        // Registrar en historial si el usuario está logueado
        if (isset($_SESSION['usuario_id'])) {
            $stmtLog = $pdo->prepare("
                INSERT INTO historial_navegacion (usuario_id, email, pagina_id, boton_pulsado) 
                VALUES (:uid, :email, :pid, :boton)
            ");
            $stmtLog->execute([
                ':uid' => $_SESSION['usuario_id'],
                ':email' => $_SESSION['usuario_email'],
                ':pid' => $actual_id,
                ':boton' => $boton
            ]);
        }

        echo json_encode(["status" => "success", "ruta" => $transicion['ruta']]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No existe transisición parametrizada."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error de navegación.", "debug" => $e->getMessage()]);
}
