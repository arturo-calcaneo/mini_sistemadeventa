<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<meta name="viewport" content="initial-scale=1.0, width=device-width">
		<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
		<meta charset="utf-8">

		<!-- Library: Bootstrap -->
		<?=Asset::css('bootstrap.css')?>
		<!-- Library: Template HTML -->
		<?=Asset::css('application-a07755f5.css')?>
		<!-- Library: Font Awesome & Template CSS -->
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

		<title>Iniciar Sesion</title>
	</head>
	<body class="login">
		<div class='wrapper'>
	      <div class='row'>
	        <div class='col-lg-12'>
	          <div class='brand text-center'>
	            <h1>
	              <i class='icon-bar-chart'></i>
	              SysSales
	            </h1>
	          </div>
	        </div>
	      </div>
	      <div class='row'>
	        <div class='col-lg-12'>
	          <form method="POST">
	            <fieldset class='text-center'>
	              <legend>Ingresa con tu cuenta</legend>
	              <div class='form-group'>
	                <input class='form-control' placeholder='Direccion de Correo' name="correo" type='email'>
	              </div>
	              <div class='form-group'>
	                <input class='form-control' placeholder='Contraseña' name="contra" type='password'>
	              </div>
	              <div class='text-center'>
	                <div class="mb-4">
	                	<button type="submit" class="btn btn-primary w-100">Iniciar Sesion</button>
	                	<!-- <button type="submit" class="btn btn-default">Nueva Cuenta</button> -->
	                </div>

	                <a href="#">Olvidaste tu contraseña?</a>
	              </div>
	            </fieldset>
	          </form>
	        </div>
	      </div>
	    </div>
	    <!-- Footer -->

	    <!-- Javascripts -->
	    <?=Asset::js('jquery.min.js')?>
	    <?=Asset::js('jquery-ui.min.js')?>
	    <?=Asset::js('modernizr.min.js')?>
	    <?=Asset::js('application-985b892b.js')?>
	</body>
</html>