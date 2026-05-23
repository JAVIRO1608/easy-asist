<?php
// 1. Desactivamos la salida directa de errores para evitar romper el formato JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// 2. Cabecera obligatoria para responder en JSON limpio
header('Content-Type: application/json; charset=utf-8');

// 3. Configuración de conexión a PostgreSQL
$host     = "192.108.1.11";
$port     = "5432";
$dbname   = "easy_assist_db"; 
$user     = "postgres";        
$password = "pirineus";   

$connection_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";

// 🛠️ El símbolo '@' previene que PostgreSQL inyecte texto HTML si la autenticación falla
$db = @pg_connect($connection_string);

if (!$db) {
    // Respondemos con un JSON impecable para que auth.js muestre el mensaje en el cuadro rojo
    echo json_encode([
        "success" => false, 
        "error" => "Error de conexión: Credenciales incorrectas o PostgreSQL no acepta contraseñas en localhost."
    ]);
    exit;
}

// 4. Capturar el JSON seguro que envía el JavaScript
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['accion'])) {
    echo json_encode(["success" => false, "error" => "Petición no válida o datos mal formateados."]);
    exit;
}

$accion = $data['accion'];

// =========================================================================
// ACCIÓN: REGISTRAR USUARIO
// =========================================================================
if ($accion === 'registrar') {
    $nombre   = isset($data['nombre']) ? trim($data['nombre']) : '';
    $email    = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (empty($nombre) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Verificar si el correo ya existe
    $query_check = "SELECT id FROM usuarios WHERE email = $1";
    $result_check = pg_query_params($db, $query_check, array($email));
    
    if (pg_num_rows($result_check) > 0) {
        echo json_encode(["success" => false, "error" => "El correo electrónico ya está registrado."]);
        exit;
    }

    // Encriptar contraseña por seguridad
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // Insertar usuario
    $query_insert = "INSERT INTO usuarios (nombre, email, password) VALUES ($1, $2, $3)";
    $result_insert = pg_query_params($db, $query_insert, array($nombre, $email, $password_hashed));

    if ($result_insert) {
        echo json_encode([
            "success" => true,
            "nombre" => $nombre,
            "email" => $email
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Error interno al guardar el usuario en la base de datos."]);
    }
    exit;
}

// =========================================================================
// ACCIÓN: INICIAR SESIÓN (LOGIN)
// =========================================================================
if ($accion === 'login') {
    $email    = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "error" => "Email y contraseña requeridos."]);
        exit;
    }

    $query = "SELECT nombre, email, password FROM usuarios WHERE email = $1";
    $result = pg_query_params($db, $query, array($email));

    if (pg_num_rows($result) === 1) {
        $user_data = pg_fetch_assoc($result);
        
        // Verificar contraseña encriptada
        if (password_verify($password, $user_data['password'])) {
            echo json_encode([
                "success" => true,
                "nombre" => $user_data['nombre'],
                "email" => $user_data['email']
            ]);
        } else {
            echo json_encode(["success" => false, "error" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "El usuario no existe."]);
    }
    exit;
}

echo json_encode(["success" => false, "error" => "Acción desconocida."]);
