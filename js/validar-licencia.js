/*
	Datos para consultar: 
	+ RUT Paciente
	+ FolioLicenciaMedica
	+ CodigoDeVerificacion

	Datos que mostrará:
	- CodigoDeVerificacion
	- RutPaciente
	- Nombre completo
	- FolioLicenciaMedica
	- Lugar de otorgamiento
	- Fecha de otorgamiento
	- Inst. Salud Previsional
	- Nombre del médico
	- RutEmpleador
	- Razón Social
	- Listado de tramitaciones 
*/ 

// Data EXCEL
const sectionTwo = document.querySelector('.section-two'); 

const btnConsult = document.querySelector('.btn-consult');
btnConsult.addEventListener('click', consultarLicencia);

async function consultarLicencia() {
	try {
		const RUT = document.querySelector('input[name="txt_rut"]');
		const FOLIO = document.querySelector('input[name="txt_folio"]');
		const CODE = document.querySelector('input[name="txt_cod"]');

		// Validar campos
		if (!RUT.value || !FOLIO.value || !CODE.value) {
			alert('Todos los campos son requeridos');
			return;
		}

		// Ocultar resultados anteriores
		sectionTwo.style.display = 'none';

		// Preparar datos
		const datos = {
			rut_paciente: RUT.value.trim(),
			folio_licencia: FOLIO.value.trim(),
			codigo_verificacion: CODE.value.trim()
		};

		console.log('Enviando datos:', datos);

		try {
			// Realizar la consulta
			const response = await fetch('./api/validar_licencia.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify(datos)
			});

			console.log('Respuesta de la consulta:', response);

			let data;
			const contentType = response.headers.get("content-type");
			if (contentType && contentType.includes("application/json")) {
				data = await response.json();
			} else {
				// Si no es JSON, leer como texto para debugging
				const text = await response.text();
				console.error('Respuesta no JSON:', text);
				throw new Error('La respuesta del servidor no es JSON válido');
			}

			console.log('Respuesta del servidor:', data);

			if (!data.success) {
				throw new Error(data.message || 'Error al consultar la licencia');
			}

			const licencia = data.data;
			console.log('Datos de licencia:', licencia);

			// Mostrar sección de resultados
			sectionTwo.style.display = 'block';

			// Actualizar datos básicos
			document.querySelector('.rut-patient').textContent = licencia.rut_paciente || 'N/A';
			document.querySelector('.fullname').textContent = licencia.nombre_completo || 'N/A';
			document.querySelector('.folio').textContent = licencia.folio_licencia || 'N/A';
			document.querySelector('.place-of-granting').textContent = licencia.lugar_otorgamiento || 'N/A';
			document.querySelector('.date-of-granting').textContent = licencia.fecha_otorgamiento || 'N/A';
			document.querySelector('.inst-salud').textContent = licencia.inst_salud_previsional || 'N/A';
			document.querySelector('.medic-fullname').textContent = licencia.nombre_medico || 'N/A';
			document.querySelector('.rut-emp').textContent = licencia.rut_empleador || 'N/A';
			document.querySelector('.social').textContent = licencia.razon_social || 'N/A';
			document.querySelector('.pdf-archive').href = `./upload/archivos/${licencia.archivo_pdf}`;

			// Actualizar tabla de tramitaciones
			const tbody = document.querySelector('.tbody');
			tbody.innerHTML = '';

			if (licencia.tramitaciones && licencia.tramitaciones.length > 0) {
				licencia.tramitaciones.forEach(tramite => {
					tbody.innerHTML += `
						<tr>
							<td>${tramite.fecha || 'N/A'}</td>
							<td>${tramite.estado || 'N/A'}</td>
							<td>${tramite.entidad || 'N/A'}</td>
						</tr>
					`;
				});
			} else {
				tbody.innerHTML = '<tr><td colspan="3" class="text-center">No hay tramitaciones registradas</td></tr>';
			}

		} catch (fetchError) {
			console.error('Error en la petición:', fetchError);
			throw new Error('Error de comunicación con el servidor: ' + fetchError.message);
		}

	} catch (error) {
		console.error('Error:', error);
		alert(error.message || 'Error al consultar la licencia');
		sectionTwo.style.display = 'none';
	}
}