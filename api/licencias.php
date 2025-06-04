<?php
require_once '../config/database.php';
require_once '../includes/crud.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

function sendJsonResponse($success, $data = null, $message = '') {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ]);
    exit;
}

try {
    $database = new Database();
    if (!$database->createDatabaseIfNotExists()) {
        throw new Exception('Error al inicializar la base de datos');
    }
    
    $db = $database->getConnection();
    if (!$db) {
        throw new Exception('Error de conexión a la base de datos');
    }

    $licencia = new LicenciaMedica($db);

    // GET - Obtener licencia(s)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $result = $licencia->read($id);
        sendJsonResponse(true, $result);
    }
    
    // POST - Crear o actualizar licencia
    else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si se recibieron los datos JSON
        if (!isset($_POST['datos'])) {
            throw new Exception('No se recibieron los datos de la licencia');
        }

        $datos = json_decode($_POST['datos'], true);
        if (!$datos) {
            throw new Exception('Error al decodificar los datos JSON');
        }

        // Procesar el archivo PDF si existe
        if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['archivo_pdf'];
            
            // Validar tipo de archivo
            $tipo = mime_content_type($archivo['tmp_name']);
            if ($tipo !== 'application/pdf') {
                throw new Exception('Solo se permiten archivos PDF');
            }

            // Validar tamaño (5MB máximo)
            if ($archivo['size'] > 5 * 1024 * 1024) {
                throw new Exception('El archivo no debe superar los 5MB');
            }

            // Crear directorio si no existe
            $directorio = '../upload/archivos/';
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            // Generar nombre único para el archivo
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
            $rutaArchivo = $directorio . $nombreArchivo;

            // Mover el archivo
            if (!move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                throw new Exception('Error al guardar el archivo');
            }

            $datos['archivo_pdf'] = $nombreArchivo;
        }

        // Crear o actualizar la licencia
        $id = isset($datos['id']) ? $datos['id'] : null;
        if ($id) {
            // Si es actualización, obtener datos anteriores
            $licenciaAnterior = $licencia->read($id)[0];
            
            // Si hay un archivo nuevo y existe uno anterior, eliminar el anterior
            if (isset($datos['archivo_pdf']) && !empty($licenciaAnterior['archivo_pdf'])) {
                $rutaAnterior = '../upload/archivos/' . $licenciaAnterior['archivo_pdf'];
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }
            
            // Si no se subió un archivo nuevo, mantener el anterior
            if (!isset($datos['archivo_pdf']) && isset($licenciaAnterior['archivo_pdf'])) {
                $datos['archivo_pdf'] = $licenciaAnterior['archivo_pdf'];
            }

            $result = $licencia->update($id, $datos);
        } else {
            $result = $licencia->create($datos);
        }

        if ($result) {
            sendJsonResponse(true, null, 'Licencia guardada exitosamente');
        } else {
            throw new Exception('Error al guardar la licencia');
        }
    }
    
    // DELETE - Eliminar licencia
    else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            throw new Exception('ID no proporcionado');
        }

        // Obtener información de la licencia antes de eliminarla
        $licenciaInfo = $licencia->read($id)[0];
        
        // Eliminar el archivo si existe
        if (!empty($licenciaInfo['archivo_pdf'])) {
            $rutaArchivo = '../upload/archivos/' . $licenciaInfo['archivo_pdf'];
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
        }

        if ($licencia->delete($id)) {
            sendJsonResponse(true, null, 'Licencia eliminada exitosamente');
        } else {
            throw new Exception('Error al eliminar la licencia');
        }
    }
    
    else {
        throw new Exception('Método no permitido');
    }

} catch (Exception $e) {
    error_log("Error en licencias.php: " . $e->getMessage());
    sendJsonResponse(false, null, $e->getMessage());
} 