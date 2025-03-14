$(document).ready(function(){
	$('.dropdown').on('show.bs.dropdown', function(e) {
		$(this).find('.dropdown-menu').first().stop(true, true).slideDown("200");
	});

	// ADD SLIDEUP ANIMATION TO DROPDOWN //
	$('.dropdown').on('hide.bs.dropdown', function(e) {
		$(this).find('.dropdown-menu').first().stop(true, true).slideUp("200");
	});
	
})

/**
 * Elimina todos los hijos de un elemento DOM especificado, limpiando su contenido.
 * Itera sobre los hijos del elemento y los elimina uno por uno hasta que no queden más.
 */
function removeChilds(elemento) {
    while (elemento.firstChild) {
        elemento.removeChild(elemento.firstChild);
    }
}

/**
 * Carga y muestra mensajes dinámicamente en la página tras su completa carga.
 * Utiliza fetch para obtener mensajes desde el servidor, procesa la respuesta JSON, y actualiza el DOM insertando mensajes en las ubicaciones correspondientes basadas en su tipo (médico, empleador, trabajador).
 * Antes de insertar nuevos mensajes, elimina cualquier contenido previo. Gestiona errores de la solicitud para asegurar la estabilidad de la página.
 */
// document.addEventListener('DOMContentLoaded', function() {
//     // Obtener referencias a los elementos del DOM por ID
//     const divPadreMedico = document.getElementById('MsgLoginMedico');
//     const divPadreEmpleador = document.getElementById('MsgLoginEmpleador');
//     const divPadreTrabajador = document.getElementById('MsgLoginTrabajador');

//     // Verificar si existe al menos uno de los elementos antes de realizar la solicitud fetch
//     if (!divPadreMedico && !divPadreEmpleador && !divPadreTrabajador) {
//         return;
//     }

//     // Si existe al menos uno, proceder con la solicitud fetch
//     fetch(window.location.protocol + "//" + window.location.host +'/WebAppDis/Ajax/getMsgLogin.php', {
//         headers: {
//             'Cache-Control': 'no-cache'
//         }
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error('Algo salió mal en la solicitud fetch');
//         }
//         return response.json();
//     })
//     .then(data => {
//         let mensajes = data;

//         /**
//          * Eliminar los divs existentes dentro de cada uno si existen
//          */
//         if (divPadreMedico) removeChilds(divPadreMedico);
//         if (divPadreEmpleador) removeChilds(divPadreEmpleador);
//         if (divPadreTrabajador) removeChilds(divPadreTrabajador);

//         /** 
//          * Verifica si 'mensajes' es un array y tiene elementos
//          */
//         if(Array.isArray(mensajes) && mensajes.length > 0) {
//             mensajes.forEach(mensaje => {
//                 let json_opciones = mensaje.OPCIONES.replace(/\\/g, '');
//                 let opcion = JSON.parse(json_opciones);
//                 let nuevoDiv = document.createElement('div');
//                 nuevoDiv.id = 'ejemplo_mensaje';
//                 nuevoDiv.className = `alert alert-${opcion.color || 'info'}`;
//                 nuevoDiv.setAttribute('role', 'alert');
//                 nuevoDiv.innerHTML = mensaje.MENSAJE || 'Generando aviso a clientes'; // Si el mensaje por alguna razon llega vacio este sera el mensaje por defecto

//                 switch (opcion.ubicacion) {
//                     case "medico":
//                         if (divPadreMedico) divPadreMedico.appendChild(nuevoDiv);
//                         break;
//                     case "empleador":
//                         if (divPadreEmpleador) divPadreEmpleador.appendChild(nuevoDiv);
//                         break;
//                     case "trabajador":
//                         if (divPadreTrabajador) divPadreTrabajador.appendChild(nuevoDiv);
//                         break;
//                     case "todos":
//                         if (divPadreMedico) divPadreMedico.appendChild(nuevoDiv.cloneNode(true));
//                         if (divPadreEmpleador) divPadreEmpleador.appendChild(nuevoDiv.cloneNode(true));
//                         if (divPadreTrabajador) divPadreTrabajador.appendChild(nuevoDiv);
//                         break;
//                 }
//             });
//         }        
//     })
//     .catch(error => {
//         console.error('Hubo un problema con la operación fetch: ', error);
//     });
// });