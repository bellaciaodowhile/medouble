
function enviau()

{

	var f1=document.usuario;

	

	if (ValidaRut(f1.txt_user,'Rut',true)==false){  }

	else

	{

		document.forms["usuario"].action='../WebApp/webadmin/valida.php'  

		document.forms["usuario"].target="_blank";

		document.forms["usuario"].submit();

		f1.txt_user.value="";

		f1.txt_clave.value=""; 

	}

}