<?php
// Deshabilitar la salida de errores de PHP
ini_set('display_errors', 0);
error_reporting(0);

// Asegurar que solo devolvemos JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Función para devolver respuesta JSON
function sendJsonResponse($success, $data, $message = '') {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ]);
    exit;
}

try {
    // Verificar método de la petición
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse(false, null, 'Método no permitido');
    }

    // Obtener y validar datos del POST
    $input = file_get_contents('php://input');
    if (empty($input)) {
        sendJsonResponse(false, null, 'No se recibieron datos');
    }

    $data = json_decode($input, true);
    if (!$data) {
        sendJsonResponse(false, null, 'Datos JSON inválidos');
    }

    // Validar campos requeridos
    $required_fields = ['rut_paciente', 'folio_licencia', 'codigo_verificacion'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            sendJsonResponse(false, null, "Campo requerido faltante: {$field}");
        }
    }

    // Incluir archivos necesarios
    require_once '../config/database.php';
    require_once '../includes/crud.php';

    // Crear conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();
    if (!$db) {
        sendJsonResponse(false, null, 'Error de conexión a la base de datos');
    }

    // Consulta SQL
    $sql = "SELECT l.*, 
            GROUP_CONCAT(
                JSON_OBJECT(
                    'id', t.id,
                    'fecha', t.fecha,
                    'estado', t.estado,
                    'entidad', t.entidad
                )
            ) as tramitaciones
            FROM licencias_medicas l
            LEFT JOIN tramitaciones t ON l.id = t.licencia_id
            WHERE l.rut_paciente = ? 
            AND l.folio_licencia = ? 
            AND l.codigo_verificacion = ?
            GROUP BY l.id";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        sendJsonResponse(false, null, 'Error en la preparación de la consulta: ' . $db->error);
    }

    $rut = trim($data['rut_paciente']);
    $folio = trim($data['folio_licencia']);
    $codigo = trim($data['codigo_verificacion']);

    $stmt->bind_param("sss", $rut, $folio, $codigo);
    
    if (!$stmt->execute()) {
        sendJsonResponse(false, null, 'Error al ejecutar la consulta');
    }

    $result = $stmt->get_result();
    $licencia = $result->fetch_assoc();

    if (!$licencia) {
        sendJsonResponse(false, null, 'No se encontró la licencia médica');
    }

    // Procesar las tramitaciones
    if (!empty($licencia['tramitaciones'])) {
        $tramitaciones = explode(',', $licencia['tramitaciones']);
        $tramitaciones_array = [];
        
        foreach ($tramitaciones as $tramitacion) {
            if (empty(trim($tramitacion))) continue;
            
            $decoded = json_decode(trim($tramitacion), true);
            if ($decoded && isset($decoded['id'])) {
                $tramitaciones_array[] = $decoded;
            }
        }
        
        $licencia['tramitaciones'] = $tramitaciones_array;
    } else {
        $licencia['tramitaciones'] = [];
    }

    // Formatear fechas
    if (!empty($licencia['fecha_otorgamiento'])) {
        $fecha = new DateTime($licencia['fecha_otorgamiento']);
        $licencia['fecha_otorgamiento'] = $fecha->format('d/m/Y');
    }

    sendJsonResponse(true, $licencia, '');

} catch (Exception $e) {
    error_log("Error en validar_licencia.php: " . $e->getMessage());
    sendJsonResponse(false, null, $e->getMessage());
} catch (Error $e) {
    error_log("Error fatal en validar_licencia.php: " . $e->getMessage());
    sendJsonResponse(false, null, 'Error interno del servidor');
} 