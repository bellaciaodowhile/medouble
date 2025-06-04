<?php
require_once __DIR__ . '/../config/database.php';

class LicenciaMedica {
    private $conn;
    private $table_name = "licencias_medicas";

    public function __construct($connection) {
        if (!$connection) {
            throw new Exception("Se requiere una conexión válida a la base de datos");
        }
        $this->conn = $connection;
    }

    public function create($data) {
        $this->conn->begin_transaction();

        try {
            // Validar datos requeridos
            $required_fields = [
                'codigo_verificacion', 'rut_paciente', 'nombre_completo', 'folio_licencia',
                'lugar_otorgamiento', 'fecha_otorgamiento', 'inst_salud_previsional',
                'nombre_medico', 'rut_empleador', 'razon_social'
            ];

            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Campo requerido faltante: {$field}");
                }
            }

            $sql = "INSERT INTO {$this->table_name} (
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
                archivo_pdf
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
            }

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
                $data['archivo_pdf']
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al crear la licencia médica: " . $stmt->error);
            }

            $licencia_id = $this->conn->insert_id;

            // Insertar tramitaciones
            if (isset($data['tramitaciones']) && is_array($data['tramitaciones'])) {
                $sql = "INSERT INTO tramitaciones (licencia_id, fecha, estado, entidad) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error en la preparación de la consulta de tramitaciones: " . $this->conn->error);
                }

                foreach ($data['tramitaciones'] as $tramitacion) {
                    $stmt->bind_param("isss",
                        $licencia_id,
                        $tramitacion['fecha'],
                        $tramitacion['estado'],
                        $tramitacion['entidad']
                    );
                    if (!$stmt->execute()) {
                        throw new Exception("Error al crear la tramitación: " . $stmt->error);
                    }
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error en create(): " . $e->getMessage());
            throw $e;
        }
    }

    public function read($id = null) {
        try {
            if ($id) {
                $sql = "SELECT l.*, 
                        COALESCE(GROUP_CONCAT(
                            JSON_OBJECT(
                                'id', t.id,
                                'fecha', t.fecha,
                                'estado', t.estado,
                                'entidad', t.entidad
                            )
                        ), '[]') as tramitaciones
                        FROM {$this->table_name} l
                        LEFT JOIN tramitaciones t ON l.id = t.licencia_id
                        WHERE l.id = ?
                        GROUP BY l.id";
                $stmt = $this->conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
                }
                $stmt->bind_param("i", $id);
            } else {
                $sql = "SELECT l.*, 
                        COALESCE(GROUP_CONCAT(
                            JSON_OBJECT(
                                'id', t.id,
                                'fecha', t.fecha,
                                'estado', t.estado,
                                'entidad', t.entidad
                            )
                        ), '[]') as tramitaciones
                        FROM {$this->table_name} l
                        LEFT JOIN tramitaciones t ON l.id = t.licencia_id
                        GROUP BY l.id
                        ORDER BY l.created_at DESC";
                $stmt = $this->conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
                }
            }

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $licencias = $result->fetch_all(MYSQLI_ASSOC);

            // Procesar las tramitaciones
            foreach ($licencias as &$licencia) {
                if ($licencia['tramitaciones'] !== '[]') {
                    $tramitaciones = json_decode('[' . $licencia['tramitaciones'] . ']', true);
                    $licencia['tramitaciones'] = $tramitaciones ?? [];
                } else {
                    $licencia['tramitaciones'] = [];
                }
            }

            return $licencias;

        } catch (Exception $e) {
            error_log("Error en read(): " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data) {
        $this->conn->begin_transaction();

        try {
            // Validar datos requeridos
            $required_fields = [
                'codigo_verificacion', 'rut_paciente', 'nombre_completo', 'folio_licencia',
                'lugar_otorgamiento', 'fecha_otorgamiento', 'inst_salud_previsional',
                'nombre_medico', 'rut_empleador', 'razon_social'
            ];

            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Campo requerido faltante: {$field}");
                }
            }

            $sql = "UPDATE {$this->table_name} SET 
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
                archivo_pdf = ?
                WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
            }

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
                $data['archivo_pdf'],
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar la licencia médica: " . $stmt->error);
            }

            // Eliminar tramitaciones existentes
            $sql = "DELETE FROM tramitaciones WHERE licencia_id = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de eliminación: " . $this->conn->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar las tramitaciones existentes: " . $stmt->error);
            }

            // Insertar nuevas tramitaciones
            if (isset($data['tramitaciones']) && is_array($data['tramitaciones'])) {
                $sql = "INSERT INTO tramitaciones (licencia_id, fecha, estado, entidad) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error en la preparación de la consulta de tramitaciones: " . $this->conn->error);
                }

                foreach ($data['tramitaciones'] as $tramitacion) {
                    $stmt->bind_param("isss",
                        $id,
                        $tramitacion['fecha'],
                        $tramitacion['estado'],
                        $tramitacion['entidad']
                    );
                    if (!$stmt->execute()) {
                        throw new Exception("Error al crear la tramitación: " . $stmt->error);
                    }
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error en update(): " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id) {
        try {
            $this->conn->begin_transaction();

            // Eliminar tramitaciones primero debido a la restricción de clave foránea
            $sql = "DELETE FROM tramitaciones WHERE licencia_id = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de eliminación de tramitaciones: " . $this->conn->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar las tramitaciones: " . $stmt->error);
            }

            // Eliminar la licencia médica
            $sql = "DELETE FROM {$this->table_name} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de eliminación de licencia: " . $this->conn->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar la licencia médica: " . $stmt->error);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error en delete(): " . $e->getMessage());
            throw $e;
        }
    }
} 