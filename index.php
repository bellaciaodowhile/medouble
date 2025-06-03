<html lang="en"><head>
<!-- <script disable-devtool-auto="" src="js/disable-devtool.js"></script> -->
<script src="js/jquery-1.9.1.min.js"></script>


	<meta charset="utf-8">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="images/favicon.ico">
	<title>Medipass Licencia Médica Electrónica</title>
	<link href="css/webpubliccss.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/jasny-bootstrap.min.css" rel="stylesheet">
	<link href="css/stylesheet.css" rel="stylesheet">
	<script src="js/Form.js" type="text/javascript"></script>
	<link rel="stylesheet/less" type="text/css" href="less/style.less">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
	<script src="js/less-1.3.3.min.js"></script>
	<script>

		function ValidaRut(Elemento, campo, requerido) {
			var E = Elemento;
			var rut = E.value;
			if (ValidaVacio(Elemento, campo, requerido) == false)
				return false;
			if (Elemento.value == "") {
				return true;
			}
			var tmpstr = "";
			for (i = 0; i < rut.length; i++)
				if (rut.charAt(i) != ' ' && rut.charAt(i) != '.' && rut.charAt(i) != '-')
					tmpstr = tmpstr + rut.charAt(i);
			rut = tmpstr;
			largo = rut.length;
			// [VARM+]
			tmpstr = "";
			for (i = 0; rut.charAt(i) == '0'; i++);
			for (; i < rut.length; i++)
				tmpstr = tmpstr + rut.charAt(i);
			rut = tmpstr;
			largo = rut.length;
			// [VARM-]
			if (largo < 2) {
				alert("Debe ingresar el Rut completo.");
				Elemento.focus();
				Elemento.select();
				return false;
			}
			for (i = 0; i < largo; i++) {
				if (rut.charAt(i) != "0" && rut.charAt(i) != "1" && rut.charAt(i) != "2" && rut.charAt(i) != "3" && rut.charAt(i) != "4" && rut.charAt(i) != "5" && rut.charAt(i) != "6" && rut.charAt(i) != "7" && rut.charAt(i) != "8" && rut.charAt(i) != "9" && rut.charAt(i) != "k" && rut.charAt(i) != "K") {
					alert("El valor ingresado no corresponde a un Rut válido.");
					Elemento.focus();
					Elemento.select();
					return false;
				}
			}
			var invertido = "";
			for (i = (largo - 1), j = 0; i >= 0; i-- , j++)
				invertido = invertido + rut.charAt(i);
			var drut = "";
			drut = drut + invertido.charAt(0);
			drut = drut + '-';
			cnt = 0;
			for (i = 1, j = 2; i < largo; i++ , j++) {
				if (cnt == 3) {
					drut = drut + '.';
					j++;
					drut = drut + invertido.charAt(i);
					cnt = 1;
				}
				else {
					drut = drut + invertido.charAt(i);
					cnt++;
				}
			}
			invertido = "";
			for (i = (drut.length - 1), j = 0; i >= 0; i-- , j++)
				invertido = invertido + drut.charAt(i);
			Elemento.value = invertido;
			if (checkDV(Elemento, rut, campo))
				return true;
			return false;
		}
	</script>
	<script src="js/validar-usuario.js" type="text/javascript"></script>
</head>

<body>
	<header class="header">
		<div style="height: 71px; background-color: #1DA9A3; color: rgba(255,255,255,0.87); margin-bottom: -2px;">
			<a href="#"><img src="images/logo-medipass-blanco.png" class="img-responsive img-logo"></a>
			<img class="img-responsive img-num" src="images/nuevo_numero_blanco.png">
		</div>
		<div class="navbar hidden-xs" role="navigation">
			<div class="container" style="width: 100%">
				<div class="row">
					<div>
						<div class="collapse navbar-collapse pull-right" style="margin-right: -1px;">
							<ul class="nav navbar-nav menu-principal">
								<li>
									<a href="#"> <span class="glyphicon"> <img src="images/icono-medipass.png">
										</span>Quiénes Somos</a>
								</li>
								<li>
									<a href="#"> <span class="glyphicon"> <img src="images/icono-descargas.png">
										</span>Descargas</a>
								</li>
								<li style="padding-right: 25px;">
									<a href="#"><span class="glyphicon"> <img src="images/icono-correo.png">
										</span>Contacto</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- /.navbar -->
	</header>

	<section style="height: 73%;" id="home">
		<div class="caja-tab" style="padding: 0px;">
			<div style="padding: 0px; border: none !important;">
				<div style="width: 360px; margin: auto;">
					<div class="MsgLogin" id="MsgLoginTrabajador"></div>
					<nav class="newNav">
						<ul class="nav-links-icons">
							<li>
								<a class="medico" href="#"></a>
								<a style="position: fixed; padding-top: 0px; padding-right: 58px;" href="#"></a>
								<div style="text-align: center;">Médicos</div>
							</li>
							<li>
								<a class="empleador" href="#"></a>
								<a style="position: fixed; padding-top: 0px; padding-right: 58px;" href="#"></a>
								<div style="text-align: center;">Empleadores</div>
							</li>
							<li class="last active" style="margin: 0 0px 0 -1px !important;">
								<a class="trabajador" href="#" onclick="return false;"></a>
								<div style="text-align: center;">Trabajadores</div>
							</li>
						</ul>
					</nav>
					<div class="login" style="float: initial; width: 99.9%; border: solid 4px #1DA9A3; border-top: none;">
						<strong>Consulta el estado de LME</strong>
						<div class="separador-10"></div>
						<form action="#" enctype="multipart/form-data" method="post" name="licencia">
							<div class="form-group">
								<input type="text" name="txt_rut" class="form-control" placeholder="RUT Paciente">
							</div>
							<div class="form-group">
								<input type="text" name="txt_folio" class="form-control" placeholder="Folio Licencia Médica">
							</div>
							<div class="form-group">
								<input type="text" name="txt_cod" class="form-control" placeholder="Código de verificación">
							</div>
							<a data-toggle="modal" data-target="#myModal" href="#"><small>Qué es el código de verificación </small></a>
							<div class="separador-10"> </div>
							<button name="Ingresar" type="button" class="btn btn-default btn-consult" style="background-color: #1DA9A3;">
								Consultar
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<img src="images/codigo-verificacion.jpg" class="img-responsive">
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
		<div class="container" style="width: 13em;float: right;margin-bottom: 2em;">
			<div class="row">
				<div class="col-sm-12" style="text-align: center;">
					<p>+56 2 2588 86 29</p>
					<a href="mailto:soporte@medipass.cl">soporte@medipass.cl</a>
				</div>
			</div>
		</div>
	</footer>

	<div class="section-two">
		<div class="section-two--content">
			<a href="#" class="section-two--document" target="_blank"></a>
			<div class="section-two--title">estado de la licencia médica</div>
			<div class="section-two--subtitle">Datos del Paciente:</div>

			<div class="flex-two mt-content">
				<div class="flex-one">
					<div class="section-two--subtitle">Rut:</div>
					<div class="section-two--text rut-patient">content</div>
				</div>
				<div class="flex-one">
					<div class="section-two--subtitle">Nombre:</div>
					<div class="section-two--text uppercase fullname"></div>
				</div>
			</div>
			<div class="section-two--subtitle separator">Datos del otorgamiento de la licencia médica electrónica (LME)</div>
			<div class="flex-two">
				<div class="flex-one">
					<div class="section-two--subtitle">Folio:</div>
					<div class="section-two--text folio"></div>
				</div>
				<div class="flex-one">
					<div class="section-two--subtitle">Lugar de otorgamiento:</div>
					<div class="section-two--text place-of-granting"></div>
				</div>
			</div>
			<div class="flex-two">
				<div class="flex-one">
					<div class="section-two--subtitle">Fecha de otorgamiento:</div>
					<div class="section-two--text date-of-granting">00/00/00</div>
				</div>
				<div class="flex-one">
					<div class="section-two--subtitle">Inst. Salud Previsional:</div>
					<div class="section-two--text inst-salud"></div>
				</div>
			</div>
			<div class="flex-one">
				<div class="section-two--subtitle">Nombre del médico:</div>
				<div class="section-two--text medic-fullname">Nombre Nombre Apellido Apellido</div>
			</div>
			<div class="section-two--subtitle separator">Datos del empleador</div>
			<div class="flex-two">
				<div class="flex-one">
					<div class="section-two--subtitle">Rut:</div>
					<div class="section-two--text rut-emp">content</div>
				</div>
				<div class="flex-one">
					<div class="section-two--subtitle">Razón Social:</div>
					<div class="section-two--text uppercase social">itau corredores de seguros s.a</div>
				</div>
			</div>
			<div class="section-two--subtitle separator">Tramitación de la licencia médica electrónica (LME)</div>
			<table>
				<thead>
					<tr>
						<th class="section-two--subtitle">Fecha</th>
						<th class="section-two--subtitle">Estado</th>
						<th class="section-two--subtitle">Entidad</th>
						<th class="section-two--subtitle">Observación</th>
					</tr>
				</thead>
				<tbody class="tbody"></tbody>
			</table>

			<div class="text-normal">Si usted desa ver o imprimir una copia de la licencia médica electrónica (LME), presione el siguiente link, <a href="#">*Ver licencia electrónica*</a>Aquí podrá ver los últimos datos registrados en ella. Esta copia no será válida para realizar trámites.</div>
		</div>

	</div>
	<input type="file" id="fileUpload" accept=".xls,.xlsx" class="hidden" />
	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jasny-bootstrap.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/validar-licencia.js" type="module"></script>
	<script>
		$(".cerrar").click(function () {
			$('#myNavmenu').offcanvas("hide");
		});
	</script>
	<script>
		window.dataLayer = window.dataLayer || [];
	</script>
</body>
</html>