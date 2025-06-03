<?php
require_once __DIR__ . '/../config/database.php';

class LicenciaMedica {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function create($data) {
        $this->conn->begin_transaction();

        try {
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
                razon_social
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssssssss",
                $data['codigo_verificacion'],
                $data['rut_paciente'],
                $data['nombre_completo'],
                $data['folio_licencia'],
                $data['lugar_otorgamiento'],
                $data['fecha_otorgamiento'],
                $data['inst_salud_previsional'],
                $data['nombre_medico'],
                $data['rut_empleador'],
                $data['razon_social']
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al crear la licencia médica");
            }

            $licencia_id = $this->conn->insert_id;

            // Insertar tramitaciones
            if (isset($data['tramitaciones']) && is_array($data['tramitaciones'])) {
                $sql = "INSERT INTO tramitaciones (licencia_id, fecha, estado, entidad) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);

                foreach ($data['tramitaciones'] as $tramitacion) {
                    $stmt->bind_param("isss",
                        $licencia_id,
                        $tramitacion['fecha'],
                        $tramitacion['estado'],
                        $tramitacion['entidad']
                    );
                    if (!$stmt->execute()) {
                        throw new Exception("Error al crear la tramitación");
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function read($id = null) {
        if ($id) {
            $sql = "SELECT l.*, 
                    GROUP_CONCAT(
                        IF(t.id IS NOT NULL,
                            JSON_OBJECT(
                                'id', t.id,
                                'fecha', t.fecha,
                                'estado', t.estado,
                                'entidad', t.entidad,
                                'licencia_id', t.licencia_id
                            ),
                            NULL
                        )
                        SEPARATOR '|||'
                    ) as tramitaciones
                    FROM licencias_medicas l
                    LEFT JOIN tramitaciones t ON l.id = t.licencia_id
                    WHERE l.id = ?
                    GROUP BY l.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
        } else {
            $sql = "SELECT l.*, 
                    GROUP_CONCAT(
                        IF(t.id IS NOT NULL,
                            JSON_OBJECT(
                                'id', t.id,
                                'fecha', t.fecha,
                                'estado', t.estado,
                                'entidad', t.entidad,
                                'licencia_id', t.licencia_id
                            ),
                            NULL
                        )
                        SEPARATOR '|||'
                    ) as tramitaciones
                    FROM licencias_medicas l
                    LEFT JOIN tramitaciones t ON l.id = t.licencia_id
                    GROUP BY l.id
                    ORDER BY l.created_at DESC";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $licencias = $result->fetch_all(MYSQLI_ASSOC);

        // Procesar las tramitaciones JSON
        foreach ($licencias as &$licencia) {
            if (!empty($licencia['tramitaciones'])) {
                // Usar un separador personalizado para evitar problemas con las comas
                $tramitaciones = explode('|||', $licencia['tramitaciones']);
                $tramitaciones_array = [];
                
                foreach ($tramitaciones as $tramitacion) {
                    if (empty(trim($tramitacion))) continue;
                    
                    $decoded = json_decode(trim($tramitacion), true);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        continue;
                    }
                    
                    if ($decoded && isset($decoded['id']) && $decoded['licencia_id'] == $licencia['id']) {
                        $tramitaciones_array[] = [
                            'id' => $decoded['id'],
                            'fecha' => $decoded['fecha'],
                            'estado' => $decoded['estado'],
                            'entidad' => $decoded['entidad'],
                            'licencia_id' => $decoded['licencia_id']
                        ];
                    }
                }
                
                $licencia['tramitaciones'] = $tramitaciones_array;
            } else {
                $licencia['tramitaciones'] = [];
            }
        }

        return $licencias;
    }

    public function update($id, $data) {
        $this->conn->begin_transaction();

        try {
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
                razon_social = ?
                WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssssssssi",
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
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar la licencia médica");
            }

            // Eliminar tramitaciones existentes
            $sql = "DELETE FROM tramitaciones WHERE licencia_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Insertar nuevas tramitaciones
            if (isset($data['tramitaciones']) && is_array($data['tramitaciones'])) {
                $sql = "INSERT INTO tramitaciones (licencia_id, fecha, estado, entidad) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);

                foreach ($data['tramitaciones'] as $tramitacion) {
                    $stmt->bind_param("isss",
                        $id,
                        $tramitacion['fecha'],
                        $tramitacion['estado'],
                        $tramitacion['entidad']
                    );
                    if (!$stmt->execute()) {
                        throw new Exception("Error al actualizar la tramitación");
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function delete($id) {
        // La eliminación en cascada se maneja automáticamente por la FK
        $sql = "DELETE FROM licencias_medicas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
} 