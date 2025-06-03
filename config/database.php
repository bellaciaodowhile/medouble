<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'medouble');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    $conn->select_db(DB_NAME);
} else {
    die("Error creating database: " . $conn->error);
}

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS licencias_medicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_verificacion VARCHAR(50) NOT NULL,
    rut_paciente VARCHAR(20) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    folio_licencia VARCHAR(50) NOT NULL,
    lugar_otorgamiento VARCHAR(100) NOT NULL,
    fecha_otorgamiento DATE NOT NULL,
    inst_salud_previsional VARCHAR(100) NOT NULL,
    nombre_medico VARCHAR(100) NOT NULL,
    rut_empleador VARCHAR(20) NOT NULL,
    razon_social VARCHAR(100) NOT NULL,
    estado_tramitacion VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

return $conn; 