<?php
require_once '../config/database.php';
require_once '../includes/crud.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$licencia = new LicenciaMedica($conn);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Get JSON data for POST and PUT requests
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Read single license or all licenses
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $result = $licencia->read($id);
        
        if ($id) {
            if (empty($result)) {
                http_response_code(404);
                echo json_encode(['error' => 'Licencia no encontrada']);
            } else {
                echo json_encode($result[0]); // Devolver solo el primer resultado para una ID específica
            }
        } else {
            echo json_encode($result); // Devolver todos los resultados
        }
        break;

    case 'POST':
        // Create new license
        if ($licencia->create($input)) {
            http_response_code(201);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Error al crear la licencia']);
        }
        break;

    case 'PUT':
        // Update existing license
        $id = isset($_GET['id']) ? $_GET['id'] : (isset($input['id']) ? $input['id'] : null);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            break;
        }
        if ($licencia->update($id, $input)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Error al actualizar la licencia']);
        }
        break;

    case 'DELETE':
        // Delete license
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            break;
        }
        if ($licencia->delete($id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Error al eliminar la licencia']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
} 