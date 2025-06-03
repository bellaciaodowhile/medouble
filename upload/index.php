<?php
require_once '../config/database.php';
require_once '../includes/crud.php';

$licencia = new LicenciaMedica($conn);
$licencias = $licencia->read();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Licencias Médicas - Medipass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="images/favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .modal-overlay {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .modal-overlay.active {
            opacity: 1;
        }
        .modal-container {
            transform: scale(0.95);
            opacity: 0;
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .modal-container.active {
            transform: scale(1);
            opacity: 1;
        }
        .tramitacion-content {
            visibility: hidden;
            background-color: #f9fafb;
        }
        .tramitacion-content > td {
            padding: 0;
        }
        .tramitacion-content > td > div {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s ease-out;
        }
        .tramitacion-content.expanded {
            visibility: visible;
        }
        .tramitacion-content.expanded > td > div {
            max-height: 500px;
            opacity: 1;
            padding: 1rem;
        }
        .rotate-icon {
            transition: transform 0.3s ease;
        }
        .rotate-icon.expanded {
            transform: rotate(180deg);
        }
        @media (max-width: 640px) {
            .table-container {
                margin: -1rem;
            }
            .responsive-table {
                display: block;
            }
            .responsive-table thead {
                display: none;
            }
            .responsive-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border-bottom: 2px solid #e5e7eb;
            }
            .responsive-table tbody td {
                display: block;
                text-align: right;
                padding: 0.5rem 1rem;
                position: relative;
            }
            .responsive-table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                font-weight: 600;
                text-align: left;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <nav class="bg-[#1DA9A3] p-4 sticky top-0 z-10 shadow-lg">
            <div class="container mx-auto flex flex-col sm:flex-row justify-between items-center">
                <h1 class="text-white text-xl sm:text-2xl font-bold">Gestión de Licencias Médicas</h1>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <!-- Add License Button -->
            <div class="mb-6">
                <button onclick="openModal()" class="bg-[#1DA9A3] text-white px-6 py-3 rounded-lg hover:bg-[#178F89] transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Agregar Nueva Licencia</span>
                </button>
            </div>

            <!-- Licenses Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="table-container overflow-x-auto overflow-hidden">
                    <table class="min-w-full responsive-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RUT Paciente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Tramitación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($licencias as $lic): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4" data-label="Código"><?php echo htmlspecialchars($lic['codigo_verificacion']); ?></td>
                                <td class="px-6 py-4" data-label="RUT"><?php echo htmlspecialchars($lic['rut_paciente']); ?></td>
                                <td class="px-6 py-4" data-label="Nombre"><?php echo htmlspecialchars($lic['nombre_completo']); ?></td>
                                <td class="px-6 py-4" data-label="Folio"><?php echo htmlspecialchars($lic['folio_licencia']); ?></td>
                                <td class="px-6 py-4" data-label="Fecha"><?php echo htmlspecialchars($lic['fecha_otorgamiento']); ?></td>
                                <td class="px-6 py-4" data-label="Estado">
                                    <?php 
                                    $tramitaciones = $lic['tramitaciones'];
                                    $ultima_tramitacion = end($tramitaciones);
                                    if ($ultima_tramitacion): 
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $ultima_tramitacion['estado'] === 'Aprobada' ? 'bg-green-100 text-green-800' : 
                                        ($ultima_tramitacion['estado'] === 'Rechazada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                        <?php echo htmlspecialchars($ultima_tramitacion['estado']); ?>
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2" data-label="Acciones">
                                    <button onclick="toggleTramitaciones(<?php echo $lic['id']; ?>)" 
                                            class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline rotate-icon" id="icon-<?php echo $lic['id']; ?>" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button onclick="editLicencia(<?php echo $lic['id']; ?>)" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteLicencia(<?php echo $lic['id']; ?>)" 
                                            class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr class="tramitacion-content" id="tramitaciones-<?php echo $lic['id']; ?>">
                                <td colspan="7">
                                    <div class="transition-all duration-300">
                                        <script>
                                            console.log(<?php echo json_encode($lic['tramitaciones']); ?>);
                                        </script>
                                        <?php 
                                        $tramitaciones = $lic['tramitaciones'];
                                        if (!empty($tramitaciones)): 
                                        ?>
                                            <div class="space-y-4 p-4">
                                                <h4 class="font-semibold text-gray-900 mb-3">Historial de Tramitaciones</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    <?php 
                                                    foreach ($tramitaciones as $tramitacion): 
                                                        if (!empty($tramitacion['fecha'])):
                                                    ?>
                                                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                                            <div class="flex justify-between items-start mb-2">
                                                                <div class="text-sm font-medium text-gray-900">
                                                                    <?php echo date('d/m/Y', strtotime($tramitacion['fecha'])); ?>
                                                                </div>
                                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                                    <?php echo $tramitacion['estado'] === 'Aprobada' ? 'bg-green-100 text-green-800' : 
                                                                    ($tramitacion['estado'] === 'Rechazada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                                                    <?php echo htmlspecialchars($tramitacion['estado']); ?>
                                                                </span>
                                                            </div>
                                                            <div class="text-sm text-gray-600">
                                                                <div class="mb-1">
                                                                    <span class="font-medium">Entidad:</span> 
                                                                    <?php echo htmlspecialchars($tramitacion['entidad']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php 
                                                        endif;
                                                    endforeach; 
                                                    ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center text-gray-500 py-4">
                                                No hay tramitaciones registradas para esta licencia
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="licenciaModal" class="modal-overlay hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="modal-container relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">Nueva Licencia Médica</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="licenciaForm" class="space-y-6">
                    <input type="hidden" id="licenciaId" name="id">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Código de Verificación</label>
                            <input type="text" id="codigo_verificacion" name="codigo_verificacion" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">RUT Paciente</label>
                            <input type="text" id="rut_paciente" name="rut_paciente" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                            <input type="text" id="nombre_completo" name="nombre_completo" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Folio Licencia</label>
                            <input type="text" id="folio_licencia" name="folio_licencia" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lugar de Otorgamiento</label>
                            <input type="text" id="lugar_otorgamiento" name="lugar_otorgamiento" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Otorgamiento</label>
                            <input type="date" id="fecha_otorgamiento" name="fecha_otorgamiento" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Institución de Salud</label>
                            <input type="text" id="inst_salud_previsional" name="inst_salud_previsional"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Médico</label>
                            <input type="text" id="nombre_medico" name="nombre_medico" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">RUT Empleador</label>
                            <input type="text" id="rut_empleador" name="rut_empleador" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Razón Social</label>
                            <input type="text" id="razon_social" name="razon_social" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Tramitación</label>
                            <select id="estado_tramitacion" name="estado_tramitacion" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                                <option value="En Trámite">En Trámite</option>
                                <option value="Aprobada">Aprobada</option>
                                <option value="Rechazada">Rechazada</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sección de Tramitaciones -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-semibold text-gray-900">Tramitaciones</h4>
                            <button type="button" onclick="agregarTramitacion()" 
                                    class="px-3 py-1 bg-[#1DA9A3] text-white rounded-md hover:bg-[#178F89] transition-all duration-200 transform hover:scale-105 flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                <span>Agregar Tramitación</span>
                            </button>
                        </div>
                        <div id="tramitacionesContainer" class="space-y-4">
                            <!-- Las tramitaciones se agregarán aquí dinámicamente -->
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeModal()" 
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-all duration-200 transform hover:scale-105">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-[#1DA9A3] text-white rounded-md hover:bg-[#178F89] transition-all duration-200 transform hover:scale-105">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let tramitacionCounter = 0;

        function toggleTramitaciones(id) {
            const content = document.getElementById(`tramitaciones-${id}`);
            const icon = document.getElementById(`icon-${id}`);
            
            // Asegurarse de que la fila sea visible antes de animar
            if (!content.classList.contains('expanded')) {
                content.style.display = 'table-row';
                // Forzar un reflow para que la animación funcione
                void content.offsetHeight;
            }
            
            content.classList.toggle('expanded');
            icon.classList.toggle('expanded');
            
            // Si se está contrayendo, esperar a que termine la animación antes de ocultar
            if (!content.classList.contains('expanded')) {
                setTimeout(() => {
                    if (!content.classList.contains('expanded')) {
                        content.style.display = 'none';
                    }
                }, 300); // Este tiempo debe coincidir con la duración de la transición en CSS
            }
        }

        function agregarTramitacion(data = null) {
            const container = document.getElementById('tramitacionesContainer');
            const tramitacionId = tramitacionCounter++;
            
            const tramitacionHTML = `
                <div class="tramitacion-item bg-gray-50 p-4 rounded-lg relative" id="tramitacion-${tramitacionId}">
                    <button type="button" onclick="eliminarTramitacion(${tramitacionId})" 
                            class="absolute top-2 right-2 text-gray-400 hover:text-red-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" name="tramitaciones[${tramitacionId}][fecha]" 
                                   value="${data ? data.fecha : ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select name="tramitaciones[${tramitacionId}][estado]" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                                <option value="En Trámite" ${data && data.estado === 'En Trámite' ? 'selected' : ''}>En Trámite</option>
                                <option value="Aprobada" ${data && data.estado === 'Aprobada' ? 'selected' : ''}>Aprobada</option>
                                <option value="Rechazada" ${data && data.estado === 'Rechazada' ? 'selected' : ''}>Rechazada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Entidad</label>
                            <input type="text" name="tramitaciones[${tramitacionId}][entidad]" 
                                   value="${data ? data.entidad : ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1DA9A3] focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', tramitacionHTML);
        }

        function eliminarTramitacion(id) {
            const tramitacion = document.getElementById(`tramitacion-${id}`);
            tramitacion.remove();
        }

        function openModal(edit = false) {
            const modal = document.getElementById('licenciaModal');
            const modalContainer = modal.querySelector('.modal-container');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.add('active');
            modalContainer.classList.add('active');
            document.getElementById('modalTitle').textContent = `${edit ? 'Editar' : 'Nueva'} Licencia Médica`;
            if (!edit) {
                document.getElementById('licenciaForm').reset();
                document.getElementById('tramitacionesContainer').innerHTML = '';
                agregarTramitacion();
            }
        }

        function closeModal() {
            const modal = document.getElementById('licenciaModal');
            const modalContainer = modal.querySelector('.modal-container');
            modal.classList.remove('active');
            modalContainer.classList.remove('active');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function editLicencia(id) {
            fetch(`../api/licencias.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    const licencia = Array.isArray(data) ? data[0] : data;
                    
                    if (!licencia) {
                        alert('No se encontró la licencia');
                        return;
                    }

                    // Limpiar tramitaciones existentes
                    document.getElementById('tramitacionesContainer').innerHTML = '';

                    // Llenar campos de la licencia
                    document.getElementById('licenciaId').value = licencia.id;
                    document.getElementById('codigo_verificacion').value = licencia.codigo_verificacion;
                    document.getElementById('rut_paciente').value = licencia.rut_paciente;
                    document.getElementById('nombre_completo').value = licencia.nombre_completo;
                    document.getElementById('folio_licencia').value = licencia.folio_licencia;
                    document.getElementById('lugar_otorgamiento').value = licencia.lugar_otorgamiento;
                    document.getElementById('fecha_otorgamiento').value = licencia.fecha_otorgamiento;
                    document.getElementById('inst_salud_previsional').value = licencia.inst_salud_previsional;
                    document.getElementById('nombre_medico').value = licencia.nombre_medico;
                    document.getElementById('rut_empleador').value = licencia.rut_empleador;
                    document.getElementById('razon_social').value = licencia.razon_social;

                    // Agregar tramitaciones existentes
                    if (licencia.tramitaciones && licencia.tramitaciones.length > 0) {
                        licencia.tramitaciones.forEach(tramitacion => {
                            agregarTramitacion(tramitacion);
                        });
                    } else {
                        agregarTramitacion();
                    }

                    openModal(true);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos de la licencia');
                });
        }

        function deleteLicencia(id) {
            if (confirm('¿Está seguro de que desea eliminar esta licencia?')) {
                fetch(`../api/licencias.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar la licencia');
                    }
                });
            }
        }

        document.getElementById('licenciaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('licenciaId').value;
            
            // Convertir FormData a objeto y reorganizar tramitaciones
            const data = {};
            formData.forEach((value, key) => {
                if (key.startsWith('tramitaciones[')) {
                    const matches = key.match(/tramitaciones\[(\d+)\]\[(\w+)\]/);
                    if (matches) {
                        const [, index, field] = matches;
                        if (!data.tramitaciones) data.tramitaciones = [];
                        if (!data.tramitaciones[index]) data.tramitaciones[index] = {};
                        data.tramitaciones[index][field] = value;
                    }
                } else {
                    data[key] = value;
                }
            });

            // Convertir tramitaciones de objeto a array
            if (data.tramitaciones) {
                data.tramitaciones = Object.values(data.tramitaciones);
            }
            
            fetch('../api/licencias.php', {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al guardar la licencia');
                }
            });
        });

        // Close modal when clicking outside
        document.getElementById('licenciaModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Add escape key listener
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html> 