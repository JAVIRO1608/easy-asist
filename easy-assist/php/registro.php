<?php
// /var/www/html/easy-assist/php/registro.php

// 1. Incluimos la conexión (que ya maneja las sesiones y cabeceras CORS)
require_once 'conexion.php';

// 2. CAPTURAR EL JSON DEL FRONTEND (Esto es lo que faltaba o estaba fallando)
$input = json_decode(file_get_contents("php://input"), true);

// 3. Asignar las variables asegurando que existan
$name     = trim($input['name'] ?? '');
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

// 4. Validar que no lleguen vacías desde el cliente
if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Todos los campos son obligatorios para el registro."
    ]);
    exit;
}

try {
    // 5. Verificar si el correo ya existe en la máquina .11
    $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->fetch()) {
        http_response_code(400);
        echo json_encode([
            "status" => "error", 
            "message" => "Este correo ya está registrado."
        ]);
        exit;
    }

    // 6. Encriptar la contraseña de forma segura
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // 7. Insertar el registro limpio en PostgreSQL
    $stmtInsert = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:name, :email, :password)");
    $stmtInsert->execute([
        ':name'     => $name,
        ':email'    => $email,
        ':password' => $passwordHash
    ]);
    
    $nuevoId = $pdo->lastInsertId();

    // 8. INICIO DE SESIÓN AUTOMÁTICO
    // Guardamos los datos en la sesión para que presentacion.html los reconozca al instante
    $_SESSION['usuario_id']    = $nuevoId;
    $_SESSION['usuario_name']  = $name;
    $_SESSION['usuario_email'] = $email;

    // Respuesta final exitosa en JSON
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        "status" => "success", 
        "message" => "Usuario creado con éxito."
    ]);

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        "status" => "error", 
        "message" => "Error interno en la base de datos.", 
        "debug" => $e->getMessage()
    ]);
    exit;
}
