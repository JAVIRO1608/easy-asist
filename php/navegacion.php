<?php
// =============================================
//  EasyAssist · navegacion.php (PostgreSQL)
//  Coloca este archivo en: php/navegacion.php
// =============================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// --- Configuración BD ---
define('DB_HOST', '192.168.1.11');
define('DB_NAME', 'easyassist_db');
define('DB_USER', 'root');
define('DB_PASS', 'pirineus');
define('DB_PORT', '5432');

// --- Conexión PostgreSQL ---
function conectar() {
    try {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            DB_HOST, DB_PORT, DB_NAME
        );
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
        exit;
    }
}

// --- Leer datos del POST ---
$datos = json_decode(file_get_contents('php://input'), true);

if (!isset($datos['pagina_actual_id']) || !isset($datos['boton'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan parámetros: pagina_actual_id y boton son obligatorios']);
    exit;
}

$pagina_actual_id = (int) $datos['pagina_actual_id'];
$boton            = trim($datos['boton']);

// --- Consultar transición ---
try {
    $pdo = conectar();

    $stmt = $pdo->prepare("
        SELECT p.id, p.nombre, p.ruta
        FROM transiciones t
        JOIN paginas p ON p.id = t.pagina_destino_id
        WHERE t.pagina_actual_id = :pagina_actual_id
          AND t.boton = :boton
          AND t.activo = TRUE
        LIMIT 1
    ");

    $stmt->execute([
        ':pagina_actual_id' => $pagina_actual_id,
        ':boton'            => $boton
    ]);

    $destino = $stmt->fetch();

    if (!$destino) {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontró transición para pagina_actual_id=' . $pagina_actual_id . ' y boton=' . $boton]);
        exit;
    }

    echo json_encode([
        'id'     => $destino['id'],
        'nombre' => $destino['nombre'],
        'ruta'   => $destino['ruta']
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
