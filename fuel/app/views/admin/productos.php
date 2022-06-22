<div id="content">
	<!-- Agregar Productos -->
	<h3><i class="icon-plus-sign"></i> Nuevo Producto</h3>
	<div class='panel panel-default'>
	  <div class='panel-body'>
	  	<form method="POST" id="nuevo_producto_form" action="<?=Router::get('dashboard/nuevo-producto')?>">
	  		<div class="row">
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Marca <span style="color:red">*</span></label>
		              <input class='form-control' type='text' name="marca" required>
		            </div>
		            <div class='form-group'>
		              <label class='control-label'>Talla <span style="color:red">*</span></label>
		              <input class='form-control' type='number' name="talla" required>
		            </div>
	  			</div>
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Modelo <span style="color:red">*</span></label>
		              <input class='form-control' type='text' name="modelo" required>
		            </div>
		            <div class='form-group'>
		              <label class='control-label'>Tipo <span style="color:red">*</span></label>
		              <select class="form-control" name="tipo" required>
		              	<option value="">Selecciona una opcion:</option>
		              	<option value="Tenis">Tenis</option>
		              	<option value="Zapato">Zapato</option>
		              	<option value="Sandalia">Sandalia</option>
		              	<option value="Tacones">Tacones</option>
		              	<option value="Botas">Botas</option>
		              	<option value="Snickers">Snickers</option>
		              	<option value="Slides">Slides</option>
		              	<option value="Deportivo">Deportivo</option>
		              </select>
		            </div>
	  			</div>
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Precio <span style="color:red">*</span></label>
		              <input class='form-control' type='number' name="precio" required>
		            </div>
	  			</div>
	  		</div>

	  		<input type="submit" id="btn_enviar" style="display: none">
	  	</form>
	  	<div class="form-group">
  			<button class="btn btn-success" onclick="$('#btn_enviar').click()">Guardar</button>
  			<button class="btn" onclick="$('#nuevo_producto_form')[0].reset()">Cancelar</button>
  		</div>
	  </div>
	</div>

	<!-- Lista de Productos -->
	<h3><i class="icon-list-alt"></i> Lista de Productos</h3>

	<div class='panel panel-default'>
	  <div class='panel-body'>
	  	<!-- Tabla de Productos -->
	  	<table id="productos" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Clave</th>
	                <th>Marca</th>
	                <th>Modelo</th>
	                <th>Precio</th>
	                <th>Talla</th>
	                <th>Tipo</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
	        		foreach ($productos as $value) { ?>
	        			<tr>
	        				<td><?=$value['id_producto']?></td>
		        			<td><?=$value['marca']?></td>
		        			<td><?=$value['modelo']?></td>
		        			<td><i class="icon-money" style="font-weight: 600"></i> <?=$value['precio']?></td>
		        			<td><?=$value['talla']?></td>
		        			<td><?=$value['tipo']?></td>
	        			</tr>
	        	<?php
	        		}
	        	?>
	        </tbody>
	    </table>
	    <!-- Inicializar Tabla de Productos -->
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		$('#productos').DataTable();
	    	})
	    </script>

	  </div>
	</div>
</div>