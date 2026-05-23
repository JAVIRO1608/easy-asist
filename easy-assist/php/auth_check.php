<?php
// php/auth_check.php
require_once 'conexion.php'; // Al importar conexion.php ya hereda las cabeceras CORS y la sesión activa

if (isset($_GET['logout'])) {
    session_destroy();
    echo json_encode(["status" => "logout"]);
    exit;
}

if (isset($_SESSION['usuario_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT nombre, email, creado_en FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['usuario_id']]);
        $userData = $stmt->fetch();

        $stmtHistorial = $pdo->prepare("
            SELECT p.nombre as pagina_nombre, h.boton_pulsado, h.creado_en 
            FROM historial_navegacion h
            JOIN paginas p ON h.pagina_id = p.id
            WHERE h.usuario_id = :id
            ORDER BY h.creado_en DESC
        ");
        $stmtHistorial->execute([':id' => $_SESSION['usuario_id']]);
        $historial = $stmtHistorial->fetchAll();

        echo json_encode([
            "logged" => true,
            "user" => [
                "name" => $userData['nombre'],
                "email" => $userData['email'],
                "creado_en" => $userData['creado_en']
            ],
            "historial" => $historial
        ]);
    } catch (PDOException $e) {
        echo json_encode(["logged" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["logged" => false]);
}
