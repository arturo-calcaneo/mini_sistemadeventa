<div id="content">
	<!-- Agregar Productos -->
	<h3><i class="icon-plus-sign"></i> Nuevo Proveedor</h3>
	<div class='panel panel-default'>
	  <div class='panel-body'>
	  	<form method="POST" id="nuevo_proveedor_form" action="<?=Router::get('dashboard/nuevo-proveedor')?>">
	  		<div class="row">
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Nombre <span style="color:red">*</span></label>
		              <input class='form-control' type='text' name="nombre" required>
		            </div>
		            <div class='form-group'>
		              <label class='control-label'>Telefono <span style="color:red">*</span></label>
		              <input class='form-control' type='text' name="telefono" required>
		            </div>
	  			</div>
	  			<div class="col-md-4">
	  				<div class='form-group'>
		              <label class='control-label'>Correo <span style="color:red">*</span></label>
		              <input class='form-control' type='email' name="correo" required>
		            </div>
	  			</div>
	  		</div>
	  		<div class='form-group'>
              <label class='control-label'>Dirección <span style="color:red">*</span></label>
              <textarea class='form-control' name="direccion" required></textarea>
            </div>

	  		<input type="submit" id="btn_enviar" style="display: none">
	  	</form>
	  	<div class="form-group">
  			<button class="btn btn-success" onclick="$('#btn_enviar').click()">Guardar</button>
  			<button class="btn" onclick="$('#nuevo_proveedor_form')[0].reset()">Cancelar</button>
  		</div>
	  </div>
	</div>

	<!-- Lista de Productos -->
	<h3><i class="icon-list-alt"></i> Lista de Proveedores</h3>

	<div class='panel panel-default'>
	  <div class='panel-body'>
	  	<!-- Tabla de Proveedore -->
	  	<table id="proveedores" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Nombre</th>
	                <th>Telefono</th>
	                <th>Correo</th>
	                <th>Dirección</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
	        		foreach ($proveedores as $value) { ?>
	        			<tr>
	        				<td><?=$value['nombre']?></td>
		        			<td><?=$value['telefono']?></td>
		        			<td><?=$value['correo']?></td>
		        			<td><?=$value['direccion']?></td>
	        			</tr>
	        	<?php
	        		}
	        	?>
	        </tbody>
	    </table>
	    <!-- Inicializar Tabla de Productos -->
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		$('#proveedores').DataTable();
	    	})
	    </script>

	  </div>
	</div>
</div>