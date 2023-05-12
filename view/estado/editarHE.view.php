<?php

// session_start();
if (!isset($_SESSION["estadoAutentica"])) {
	require_once "../../config/LoadConfig.config.php";
	$config = LoadConfig::getConfig();
	header('Location:' . $config['URL_SITE'] . 'index.php');
}

if (!isset($_POST['id'])) {
	echo 'No hay datos';
} else {
?>

	<section id="five" class="wrapper style2 special fade">
		<div class="container">
			<header>
				<h3 style="color: white;">Edición Reporte Hora Extra <?= $_POST['id'] ?></h3>
				<h2 style="color: white;">Estado: <span><?= $_POST['estadoNombre'] ?></span></h2>
				<input type="text" name="idReporteHE" id="idReporteHE" data-id="<?= $_POST['id'] ?>" style="display: none;">
			</header>

			<section class="col-12 col-md-4 col-sm-12">
				<h3 style="float: left; color: white;">Comentarios: <span id="seeComments" class="fas fa-chevron-down fit"></span><span id="hideComments" class="fas fa-chevron-up fit" style="display: none;"></span></h3>
				<table>
					<tbody id="bodyComments">
						<!-- Llenar tabla -->
					</tbody>
				</table>
			</section>

			<section class="col-12 col-md-4 col-sm-12">
				<h3 style="float: left; color: white;">Añadir Comentario <span id="addComment" class="fas fit fa-plus-circle" style="color: #5480f1;"></span><span id="hideComment" class="fas fit fa-minus-circle" style="display: none; color: #5480f1;"></span></h3>
			</section>
			<br>
			<br>
			<form method="post" action="#">
				<div class="row comentarios">
					<!-- Cargar HTML Agregar comentarios -->
				</div>
			</form>
		</div>
	</section>

	<div id="formReporte">
		<!-- Cargar HTML Reporte -->
	</div>

<?php } ?>