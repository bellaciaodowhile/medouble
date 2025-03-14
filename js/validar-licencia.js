
/*
	Datos para consultar: 
	+ RUT Paciente
	+ FolioLicenciaMedica
	+ CodigoDeVerificacion

	Datos que mostrará:
	+ CodigoDeVerificacion
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

const PATH_FILE_EXCEL = '../docs/PLANTILLA.xlsx';
fetch(PATH_FILE_EXCEL)
.then(response => {
	if (!response.ok) {
		throw new Error('Network response was not ok');
	}
	return response.arrayBuffer();
})
.then(data => {
	const workbook = XLSX.read(data, { type: "array" });
	workbook.SheetNames.forEach(sheet => {
		const rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
		const jsonObject = JSON.stringify(rowObject);
		const data = JSON.parse(jsonObject)
		localStorage.setItem('DATA_EXCEL', JSON.stringify(data))
	});
})
.catch(error => {
	console.error('Error loading the Excel file:', error);
});

const sectionTwo = document.querySelector('.section-two'); 

function consult() {
	const data = JSON.parse(localStorage.getItem('DATA_EXCEL')) || [];
	const RUT = document.querySelector('input[name="txt_rut"]');
	const FOLIO = document.querySelector('input[name="txt_folio"]');
	const CODE = document.querySelector('input[name="txt_cod"]');

	if (RUT.value == '' || FOLIO.value == '' || CODE.value == '') {
		return alert('Se requiere llenar todos los campos.')
	}

	if (data.length > 0) {
		const patient = data.filter((item) =>{
			if (item.RutPaciente == RUT.value.trim() && item.Folio == FOLIO.value.trim() && item.CodigoVerificacion == CODE.value.trim()) {
				return item;
			} 
		});
		if (patient.length == 0) {
			return alert('No se encuentra el registro.')
		}

		sectionTwo.style.display = 'block';
		console.log(patient)

		const rutPatient = document.querySelector('.rut-patient');
		const fullName = document.querySelector('.fullname');
		const folio = document.querySelector('.folio');
		const placeOfGranting = document.querySelector('.place-of-granting');
		const dateOfGranting = document.querySelector('.date-of-granting');
		const instSalud = document.querySelector('.inst-salud');
		const fullNameMedic = document.querySelector('.medic-fullname');
		const rutEmp = document.querySelector('.rut-emp');
		const social = document.querySelector('.social');
		const linkPdf = document.querySelector('.section-two--document');

		rutPatient.textContent = patient[0]?.RutPaciente;
		fullName.textContent = patient[0]?.NombreCompleto;
		folio.textConten = patient[0]?.Folio;
		placeOfGranting.textContent = patient[0]?.LugarOtorgamiento;
		dateOfGranting.textContent = patient[0]?.FechaOtorgamiento;
		instSalud.textContent = patient[0]?.InstSaludPrevisional;
		fullNameMedic.textContent = patient[0]?.NombreMedico;
		rutEmp.textContent = patient[0]?.RutEmpleador;
		social.textContent = patient[0]?.RazonSocial;
		linkPdf.href = patient[0]?.LinkPdf;

		if (patient[0]?.ListadoDeTramitaciones) {
			const lists = JSON.parse(patient[0].ListadoDeTramitaciones);
			const tbody = document.querySelector('.tbody');
			const keys = ["date", "status", "doctor", "observation"];


			const test = []

			lists.forEach(item => {
				console.log(item); // Log the current item to the console

				// Convert the inner array to an object
				const obj = {
					[keys[0]]: item[0], // date
					[keys[1]]: item[1], // status
					[keys[2]]: item[2], // doctor
					[keys[3]]: item[3]  // observation
				};
				test.push(obj)

				console.log(obj); // Log the created object

				// Now you can use the object to populate the table
				
			});
			tbody.innerHTML = '';
			test.map(obj => {
				tbody.innerHTML += `<tr>`;
				tbody.innerHTML += `<td>${obj.date}</td>`;
				tbody.innerHTML += `<td>${obj.status}</td>`;
				tbody.innerHTML += `<td>${obj.doctor}</td>`;
				tbody.innerHTML += `<td>${obj.observation}</td>`;
				tbody.innerHTML += `</tr>`;
			})

			// console.log(lists)

			// tbody.innerHTML += `<tr>`; 
			// 	item.forEach((x, index) => { 
			// 		console.log(index)
			// 		tbody.innerHTML += `<td>${index}</td>`;
			// 	});
			// 	tbody.innerHTML += `</tr>`;
		}

	} else {
		alert('No existen datos registrados. Cunsulte su proveedor')
	}
}