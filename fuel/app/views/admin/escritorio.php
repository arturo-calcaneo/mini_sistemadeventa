<!-- Content -->
<div id='content'>

	<div class='panel panel-default card-statistics'>
	  <div class='panel-body' style="background-color: #fd7e14">
	  	<div class="row">
	  		<div class="col-md-6" id="left_col">
	  			<span style="font-size: 10rem">
	  				<?=$cantidadClientes?>
	  			</span>
	  		</div>
	  		<div class="col-md-6" id="right_col" style="height: 142px;position: relative;">
	  			<h3 style="position: absolute;bottom: 0;right: 0;padding-right: 2.25rem"><i class="icon-user"></i> Clientes</h3>
	  		</div>
	  	</div>
	  </div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class='panel panel-default card-statistics'>
			  <div class='panel-body' style="background-color: #dc3545">
			  	<div class="row">
			  		<div class="col-md-6" id="left_col">
			  			<span style="font-size: 10rem">
			  				<?=$productosComprados?>
			  			</span>
			  		</div>
			  		<div class="col-md-6" id="right_col" style="height: 142px;position: relative;">
			  			<h3 style="position: absolute;bottom: 0;right: 0;padding-right: 2.25rem"><i class="icon-arrow-down"></i> Productos Comprados</h3>
			  		</div>
			  	</div>
			  </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class='panel panel-default card-statistics'>
			  <div class='panel-body' style="background-color: #0d6efd">
			  	<div class="row">
			  		<div class="col-md-6" id="left_col">
			  			<span style="font-size: 10rem">
			  				<?=$productosVendidos?>
			  			</span>
			  		</div>
			  		<div class="col-md-6" id="right_col" style="height: 142px;position: relative;">
			  			<h3 style="position: absolute;bottom: 0;right: 0;padding-right: 2.25rem"><i class="icon-arrow-up"></i> Productos Vendidos</h3>
			  		</div>
			  	</div>
			  </div>
			</div>
		</div>
	</div>

	<div class='panel panel-default card-statistics'>
	  <div class='panel-body' style="background-color: #6610f2">
	  	<div class="row">
	  		<div class="col-md-6" id="left_col">
	  			<span style="font-size: 10rem">
	  				<?=$productosDisponibles?>
	  			</span>
	  		</div>
	  		<div class="col-md-6" id="right_col" style="height: 142px;position: relative;">
	  			<h3 style="position: absolute;bottom: 0;right: 0;padding-right: 2.25rem"><i class="icon-inbox"></i> Productos Disponibles</h3>
	  		</div>
	  	</div>
	  </div>
	</div>

	<div class="row">
		<div class="col-md-5">
			<div class='panel panel-default'>
			  <div class='panel-body' style="background-color: #ffc107">
			  	<div class="row">
			  		<div class="col-md-6" id="left_col">
			  			<span style="font-size: 8.85rem">
			  				<b>$</b><?=number_format($inversion,2,'.','')?>
			  			</span>
			  		</div>
			  		<div class="col-md-6" id="right_col" style="height: 142px;position: relative;">
			  			<h3 style="position: absolute;bottom: 0;right: 0;padding-right: 2.25rem"><i class="icon-dollar"></i> Inversión Total</h3>
			  		</div>
			  	</div>
			  </div>
			</div>
		</div>
		<div class="col-md-7">
			<div class='panel panel-default'>
			  <div class='panel-body' style="background-color: #20c997">
			  	<div class="row">
			  		<div class="col-md-6" id="left_col">
			  			<span style="font-size: 8.85rem">
			  				<b>$</b><?=number_format($ganancias,2,'.','')?>
			  			</span>
			  		</div>
			  		<div class="col-md-6" id="right_col" style="height: 142px;position: relative;">
			  			<h3 style="position: absolute;bottom: 0;right: 0;padding-right: 2.25rem"><i class="icon-money"></i> Ganancias Totales</h3>
			  		</div>
			  	</div>
			  </div>
			</div>
		</div>
	</div>

	<style type="text/css">
		.card-statistics * {
			color: whitesmoke
		}
		.swal2-container *{
 			font-size: 100%
		}
	</style>

	<br>
	
	<!-- Botón de Reporte de Ventas -->
	<a href="<?=Router::get('reportedeventas')?>" id="link_download_reportev" style="display:none">reporte</a>
	<button class="btn btn-danger btn-lg btn-reporte-ventas">
		<i class="icon-file-text"></i>
		Reporte de Ventas
	</button>
	<!-- Botón de Reporte de Ventas -->

	<h3>
		<i class="icon-sign-blank"></i> Movimientos de Ventas
	</h3>
	<div class='panel panel-default'>
	  <div class='panel-body'>
	  	
	  	<!-- Tabla de Movimientos -->
	  	<table id="movimientos" class="display" style="width:100%">
	  		<thead>
	            <tr>
	            	<!-- <th>Venta</th> -->
	                <th>Cliente</th>
	                <th>Correo del Cliente</th>
	                <th>Producto</th>
	                <th>Precio</th>
	                <th>Fecha de Venta</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
	        		foreach($ventas as $value){ ?>
	        			<tr>
	        				<!-- <td><?=$value['id_venta']?></td> -->
	        				<td><i class="icon-user"></i> <?=$value['nombre']?></td>
	        				<td><i class="icon-keyboard"></i> <?=$value['correo']?></td>
	        				<td><?=$value['marca']?> (<?=$value['modelo']?>)</td>
	        				<td><b>$</b> <?=$value['precio']?></td>
	        				<td><i class="icon-calendar"></i> <?=$value['fecha_de_venta']?></td>
	        			</tr>

	        	<?php
	        		}
	        	?>
	        </tbody>
	  	</table>

	  	<!-- Load DataTable -->
	  	<script type="text/javascript">
	  		$(document).ready(function(){
	  			$('#movimientos').DataTable({
	  				responsive: true,
	  				order: [[4, 'desc']]
	  			});
	  		});
	  	</script>

	  </div>
	</div>

	<style type="text/css">
		#left_col {
			height: 140px;
		}

		#right_col {
			height: 140px;
		}

		@media screen and (max-width: 991px){
			#left_col,
			#right_col {
				height: 0;
			}
		}
	</style>
</div>