<!DOCTYPE html> 
<html> 
<head> 
	<title>Sopinet/Colorize - DemoTest</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<head profile="http://www.w3.org/2005/10/profile">
	<link rel="icon" 
		    type="image/png" 
		    href="favicon.png">
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
  	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
	<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
	<style>
		<?php echo $new_css; ?>
	</style>
  	<!-- <link href="layout/bootstrap/template/template.css" rel="stylesheet">-->
</head> 
<body>
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container">

				<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<div class="bigfont">menú</div>
				</a>
				 
				<!-- Be sure to leave the brand out there if you want it shown -->
				<a class="brand" href="index.php"><img src='<?php echo $img_src; ?>'/ alt='sopinet'></a>
				 
				<!-- Everything you want hidden at 940px or less, place within here -->
				<div class="nav-collapse collapse">
	        		<ul class="nav pull-right">
						<li ><a href="what">Qué</a></li>	
						<li ><a href="who">Quién</a></li>
						<li ><a href="myblog">Blog</a></li>
						<li ><a href="budget">Cuánto</a></li>
						<li ><a href="contact">Contacto</a></li>
					</ul>
				</div>		 
			</div>
		</div>
	</div>
	<div class="description">
			<div class="container">
				soluciones por internet. Desarrollando soluciones sencillas para problemas no triviales.			</div>
	</div>

	<div class="container">

		<div class="row-fluid rownow rowpadding">
			<div class="span3 titlenow">
				<div class="mainnow"><span class="mini">Soluciones</span> Potentes</div>
				<div class="subnow">sencillez y flexibilidad</div>
			</div>
			<div class="span9 descnow">
				Trabajamos con las últimas tendencias, nos gusta prorratear nuestro aprendizaje y estamos continuamente incorporando novedades a nuestra cartera de conocimientos.
			</div>
		</div>

		<div class="row-fluid rownow rowpadding">
			<div class="span3 titlenow">
				<div class="mainnow"><span class="mini">Soluciones</span> Sinceras</div>
				<div class="subnow">máxima comunicación</div>
			</div>
			<div class="span9 descnow">
				Reportamos nuestro trabajo en un software de Gestión de Proyectos desde el cual el cliente puede comprobar cada una de las tareas que realizamos.
			</div>
		</div>

		<div class="row-fluid rowpadding">
			<div class="span3 titlenow">
				<div class="mainnow"><span class="mini">Soluciones</span> Abiertas</div>
				<div class="subnow">doble beneficio</div>
			</div>
			<div class="span9 descnow">
				Usamos frameworks opensource y facilitamos el código fuente a nuestro cliente si así lo requiere. Modularizamos cada trabajo y lo reutilizamos abaratando los costes.
			</div>
		</div>

	</div>


    <div id="footer">
			<div class="container">
				<div class="row-fluid" style="margin-top: 10px;">
					<div class="span10"></div>
					<div class="social-links span2">
					  <a class="facebook" href="http://facebook.com/sopinetcom" target="_blank"></a>
					  <a class="twitter" href="http://twitter.com/sopinetcom" target="_blank"></a>
					</div>
				</div>
			</div>
    </div>
</body>
</html>