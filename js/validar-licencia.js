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

import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

const supabaseUrl = 'https://aenlcrtjqgnxwzynorto.supabase.co'
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFlbmxjcnRqcWdueHd6eW5vcnRvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE5NTcyOTEsImV4cCI6MjA1NzUzMzI5MX0.cIFQtbPfoXvagGfW9fdg4qV_-UxvLB9luLhXqt1aFVs';
const supabase = createClient(supabaseUrl, supabaseKey)

try {
	(async () => {
		const { data, error } = await supabase
		.from('medidata')
		.select()
		console.log(data)
		if (error) return console.log(error)
		localStorage.setItem('DATA_EXCEL', JSON.stringify(data[0].data));
	})();
} catch (error) {
	console.log(`Error: ${error}`)
}


const sectionTwo = document.querySelector('.section-two'); 

const btnConsult = document.querySelector('.btn-consult');
btnConsult.addEventListener('click', consult);

function consult() {
	const dataString = JSON.parse(localStorage.getItem('DATA_EXCEL'), null, 2) || [];
	const RUT = document.querySelector('input[name="txt_rut"]');
	const FOLIO = document.querySelector('input[name="txt_folio"]');
	const CODE = document.querySelector('input[name="txt_cod"]');
	const data = JSON.parse(dataString)

	console.log(data)

	if (RUT.value == '' || FOLIO.value == '' || CODE.value == '') {
		return alert('Se requiere llenar todos los campos.')
	}

	if (data.length > 0) {
		const patient = data.filter((item) =>{
			if (item.RutPaciente.trim() == RUT.value.trim() && item.Folio.trim() == FOLIO.value.trim() && item.CodigoVerificacion.trim() == CODE.value.trim()) {
				return item;
			} 
		});
		if (patient.length == 0) {
			return alert('No se encuentra el registro.')
		}

		sectionTwo.style.display = 'block';

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
			tbody.innerHTML = '';
			lists.forEach((item, index) => {
				tbody.innerHTML += `<tr class="row-${index}"></tr>`;
			});
			lists.map((item, index) => {
				const row = document.querySelector(`.row-${index}`);
				item.map((value) => {
					row.innerHTML += `<td>${value}</td>`;
				})
			})
		}

	} else {
		alert('No existen datos registrados. Cunsulte su proveedor')
	}
}