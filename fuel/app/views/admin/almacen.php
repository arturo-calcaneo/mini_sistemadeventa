<div id="content">
	<!-- Agregar Productos -->
	<h3><i class="icon-plus-sign"></i> Nueva Compra</h3>
	<div class='panel panel-default'>
	  <div class='panel-body'>
	  	<form method="POST" id="nueva_compra_form" action="<?=Router::get('dashboard/nueva-compra')?>">
	  		<div class="row">
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Proveedor <span style="color:red">*</span></label>
		              <select class="form-control" name="idProveedor" required>
		              	<option value="">Selecciona una opcion:</option>
		              	<?php
		              		foreach ($proveedores as $value) { ?>
		              			<option value="<?=$value['id_proveedor']?>"><?=$value['nombre']?></option>
		              	<?php
		              		}
		              	?>
		              </select>
		            </div>
	  			</div>
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Producto <span style="color:red">*</span></label>
		              <select class="form-control" name="idProducto" required>
		              	<option value="">Selecciona una opcion:</option>
		              	<?php
		              		foreach ($productos as $value) { ?>
		              			<option value="<?=$value['id_producto']?>"><?=$value['marca']?> (<?=$value['modelo']?>)</option>
		              	<?php
		              		}
		              	?>
		              </select>
		            </div>
	  			</div>
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Cantidad <span style="color:red">*</span></label>
		              <input class="form-control" type="number" name="cantidad" required>
		            </div>
	  			</div>
	  		</div>

	  		<input type="submit" id="btn_enviar" style="display: none">
	  	</form>
	  	<div class="form-group">
  			<button class="btn btn-success" onclick="$('#btn_enviar').click()">Guardar</button>
  			<button class="btn" onclick="$('#nueva_compra_form')[0].reset()">Cancelar</button>
  		</div>
	  </div>
	</div>

	<!-- Lista de Productos -->
	<h3><i class="icon-list-alt"></i> Compras Realizadas</h3>

	<div class='panel panel-default'>
	  <div class='panel-body'>
	  	<!-- Tabla de Productos -->
	  	<table id="almacen" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Producto</th>
	                <th>Proveedor</th>
	                <th>Cantidad</th>
	                <th>Fecha de Compra</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
	        		foreach ($almacen as $value) { ?>
	        			<tr>
	        				<td><i class="icon-circle"></i>&#160; <?=$value['producto']?></td>
		        			<td><?=$value['proveedor']?></td>
		        			<td><?=$value['cantidad']?></td>
		        			<td><?=$value['fecha_de_compra']?></td>
	        			</tr>
	        	<?php
	        		}
	        	?>
	        </tbody>
	    </table>
	    <!-- Inicializar Tabla de Productos -->
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		$('#almacen').DataTable({
	    			responsive: true
	    		});
	    	})
	    </script>

	  </div>
	</div>
</div>