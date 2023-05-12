<?php
// session_start();
if (!isset($_SESSION["estadoAutentica"])) {
	require_once "../../config/LoadConfig.config.php";
	$config = LoadConfig::getConfig();
	header('Location:' . $config['URL_SITE'] . 'index.php');
}
?>
<section id="four" class="content" style="height: 75vh;">
	<div class="container">
		<div class="box alt">
			<section id="five" class="wrapper style2 special fade">
				<div class="container">
					<header>
						<h3 style="color: white;">Seleccione un rango de fechas</h3>
					</header>
					<form method="post" action="reporte/template.php" class="cta">
						<div class="row">
							<label for="fechaInicio" style="color: white;">Desde</label>
							<div class="col-4 col-sm-8"><input type="date" name="fechaInicio" id="fechaInicio" style="color: black" required /></div>
							<label for="fechaFin" style="color: white;">Hasta</label>
							<div class="col-4 col-sm-8"><input type="date" name="fechaFin" id="fechaFin" style="color: black" required /></div>
							<div class="col-4 col-sm-8"><input type="submit" value="Generar" class="fit primary fit small" /></div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>