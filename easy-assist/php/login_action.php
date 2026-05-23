<?php
// php/login_action.php
// ... (Tus cabeceras CORS dinámicas y el control del método OPTIONS)

require_once 'conexion.php';

$input = json_decode(file_get_contents("php://input"), true);
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT id, nombre, password FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // Verificamos si existe el usuario y si la contraseña coincide con el hash
    if ($user && password_verify($password, $user['password'])) {
        
        // ==========================================
        // LOGIN MANUAL: Activamos la sesión
        // ==========================================
        $_SESSION['usuario_id']    = $user['id'];
        $_SESSION['usuario_nome']  = $user['nombre'];
        $_SESSION['usuario_email'] = $email;

        echo json_encode(["status" => "success", "message" => "Sesión iniciada."]);
    } else {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Correo o contraseña incorrectos."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error en el servidor.", "debug" => $e->getMessage()]);
}
