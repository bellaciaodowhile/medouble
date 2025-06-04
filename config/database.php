<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'medouble';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }

            // Establecer el conjunto de caracteres
            $this->conn->set_charset("utf8");

            return $this->conn;
        } catch(Exception $e) {
            error_log("Error de conexión: " . $e->getMessage());
            return null;
        }
    }

    public function createDatabaseIfNotExists() {
        try {
            // Conectar sin seleccionar base de datos
            $tempConn = new mysqli($this->host, $this->username, $this->password);
            
            if ($tempConn->connect_error) {
                throw new Exception("Connection failed: " . $tempConn->connect_error);
            }

            // Crear base de datos si no existe
            $sql = "CREATE DATABASE IF NOT EXISTS " . $this->db_name;
            if (!$tempConn->query($sql)) {
                throw new Exception("Error creating database: " . $tempConn->error);
            }

            // Seleccionar la base de datos
            $tempConn->select_db($this->db_name);

            // Crear tabla licencias_medicas si no existe
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
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if (!$tempConn->query($sql)) {
                throw new Exception("Error creating licencias_medicas table: " . $tempConn->error);
            }

            // Crear tabla tramitaciones si no existe
            $sql = "CREATE TABLE IF NOT EXISTS tramitaciones (
                id INT AUTO_INCREMENT PRIMARY KEY,
                licencia_id INT NOT NULL,
                fecha DATE NOT NULL,
                estado VARCHAR(50) NOT NULL,
                entidad VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (licencia_id) REFERENCES licencias_medicas(id) ON DELETE CASCADE
            )";

            if (!$tempConn->query($sql)) {
                throw new Exception("Error creating tramitaciones table: " . $tempConn->error);
            }

            $tempConn->close();
            return true;

        } catch(Exception $e) {
            error_log("Error en la inicialización de la base de datos: " . $e->getMessage());
            return false;
        }
    }
} 