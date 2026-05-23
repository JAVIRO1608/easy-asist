<?php
// =============================================
//  EasyAssist · obtener_historial.php
//  Ubicación exacta: php/obtener_historial.php
// =============================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

define('DB_HOST', '192.108.1.11');
define('DB_NAME', 'easyassist_db');
define('DB_USER', 'postgres'); 
define('DB_PASS', 'pirineus');
define('DB_PORT', '5432');

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (empty($email)) {
    echo json_encode([]);
    exit;
}

try {
    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', DB_HOST, DB_PORT, DB_NAME);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Hacemos el mapeo con la tabla paginas usando tu estructura real
    $stmt = $pdo->prepare("
        SELECT h.pagina_id, h.boton_pulsado, h.creado_en, p.nombre AS nombre_pantalla
        FROM historial_navegacion h
        LEFT JOIN paginas p ON p.id = h.pagina_id
        WHERE h.email = :email
        ORDER BY h.creado_en DESC
    ");
    
    $stmt->execute([':email' => $email]);
    echo json_encode($stmt->fetchAll());

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de lectura: ' . $e->getMessage()]);
}
?>
