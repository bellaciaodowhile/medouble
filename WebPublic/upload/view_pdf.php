<?php
// Verificar que se proporcionó un nombre de archivo
if (!isset($_GET['file'])) {
    die('No se especificó ningún archivo');
}

$filename = basename($_GET['file']);
$filepath = __DIR__ . '/archivos/' . $filename;

// Verificar que el archivo existe y está dentro del directorio permitido
if (!file_exists($filepath) || !is_file($filepath)) {
    die('Archivo no encontrado');
}

// Verificar que el archivo es un PDF
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $filepath);
finfo_close($finfo);

if ($mime_type !== 'application/pdf') {
    die('Tipo de archivo no válido');
}

// Establecer las cabeceras para mostrar el PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Cache-Control: public, must-revalidate, max-age=0');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

// Leer y enviar el archivo
readfile($filepath);
exit; 