<?php
require_once __DIR__ . '/../config/database.php';

class LicenciaMedica {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function create($data) {
        $sql = "INSERT INTO licencias_medicas (
            codigo_verificacion, 
            rut_paciente, 
            nombre_completo, 
            folio_licencia, 
            lugar_otorgamiento,
            fecha_otorgamiento,
            inst_salud_previsional,
            nombre_medico,
            rut_empleador,
            razon_social,
            estado_tramitacion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssssss",
            $data['codigo_verificacion'],
            $data['rut_paciente'],
            $data['nombre_completo'],
            $data['folio_licencia'],
            $data['lugar_otorgamiento'],
            $data['fecha_otorgamiento'],
            $data['inst_salud_previsional'],
            $data['nombre_medico'],
            $data['rut_empleador'],
            $data['razon_social'],
            $data['estado_tramitacion']
        );

        return $stmt->execute();
    }

    public function read($id = null) {
        if ($id) {
            $sql = "SELECT * FROM licencias_medicas WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
        } else {
            $sql = "SELECT * FROM licencias_medicas ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE licencias_medicas SET 
            codigo_verificacion = ?,
            rut_paciente = ?,
            nombre_completo = ?,
            folio_licencia = ?,
            lugar_otorgamiento = ?,
            fecha_otorgamiento = ?,
            inst_salud_previsional = ?,
            nombre_medico = ?,
            rut_empleador = ?,
            razon_social = ?,
            estado_tramitacion = ?
            WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssssssi",
            $data['codigo_verificacion'],
            $data['rut_paciente'],
            $data['nombre_completo'],
            $data['folio_licencia'],
            $data['lugar_otorgamiento'],
            $data['fecha_otorgamiento'],
            $data['inst_salud_previsional'],
            $data['nombre_medico'],
            $data['rut_empleador'],
            $data['razon_social'],
            $data['estado_tramitacion'],
            $id
        );

        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM licencias_medicas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
} 