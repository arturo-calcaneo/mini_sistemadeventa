<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1.0, width=device-width">
		<!-- Bootstrap 5 -->
		<?=Asset::css('bootstrap.css')?>
		<!-- Library: Font Awesome -->
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!-- JQuery -->
		<?=Asset::js('jquery.min.js')?>

		<title>Tienda</title>
	</head>
	<body>
		<div class="container-fluid mt-3 mb-3">
			<!-- Barra de Navegacion -->
			<div class="p-3 mb-3">
				<div class="row">
					<div class="col-6 text-start">
						<a href="#" style="text-decoration: none" class="text-dark">
							<?=Asset::img('imagen_usuario.png', ['width' => '42'])?>
							<span style="font-size: 1.45rem;position: relative;top:4px"><?=Session::get('user-logged')['nombre']?></span>
						</a>

						<a href="<?=Router::get('carrito')?>" style="text-decoration: none;font-size: 1.33rem;position: relative;top:3px" class="text-dark ms-3">
							<i class="icon-shopping-cart"></i>
							<span style="position: relative;top:2px">Carrito (<span id="conteo_carrito"></span>)</span>
						</a>
					</div>
					<div class="col-6 text-end">
						<a 
							href="#" class="text-danger" 
							style="font-size: 1.45rem;text-decoration: none;position:relative;top:4px"
							onclick="logoutAccount('<?=Router::get('salir')?>')">
							<i class="icon-signout"></i> Salir
						</a>
					</div>
				</div>
			</div>

			<center class="mb-4">
				<hr style="width:calc(100% - 25px); border-color:rgba(0,0,0,0.4)" />
			</center>

			<!-- Notificacion de Nuevo Producto Adquirido -->
			<?php
				if(isset($_GET['message']) && trim($_GET['message']) == 'purchased'){ ?>
					<div class="p-3 mb-3 bg-info text-white" style="border-radius:7px">
						<div class="mb-2" style="font-size: 1.2rem"> 
							<i class="icon-info-sign"></i> <span>Tu compra ha sido efectuada con éxito!</span>
						</div>
						<a class="btn btn-light btn-sm" href="<?=Router::get('recibo', ['data' => Input::get('info')])?>">
							<i class="icon-indent-right"></i>
							Ver Recibo
						</a>
					</div>
			<?php
				}
			?>
			<!-- ./Notificacion de Nuevo Producto Adquirido -->

			<!-- Notificacion de Descuento en la proxima compra -->
			<?php
				if($aplicaDescuento){?>
					<div class="p-3 mb-3 bg-warning text-white" style="border-radius:7px">
						<div class="mb-2" style="font-size: 1.35rem;position: relative;top: 0.2rem">
							<i class="icon-star"></i>
							<small style="font-weight: 600">¡Has sido seleccionado para un descuento del 40% en tu proxima compra!</small>
						</div>
					</div>
			<?php
				}
			?>
			<!-- ./Notificacion de Descuento en la proxima compra -->

			<!-- Contenido del Sitio -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-2">
						<!-- Filtrar por Categorias -->
						<div class="p-3 mb-3">
							<h3 class="mb-3">
								<i class="icon-chevron-sign-down"></i>
								Categorias
							</h3>

							<div class="list-group mb-1" style="border-radius: 0">
								<?php
									foreach ($categorias as $value) { ?>
										
										<a 
											href="<?=Router::get('tipo', array('tipo' => $value['tipo']))?>" 
											class="list-group-item list-group-item-action border-0 mb-1 mt-1"
											style="font-size: 1.1rem" 
										>
											<i class="icon-circle"></i>&#160;
											<?=$value['tipo']?>
										</a>

								<?php
									}
								?>
							</div>
						</div>
						<!-- ./Filtrar por Categorias -->

						<!-- Cancelar Filtrado -->
						<?php
							if($this->tipoProducto !== null && !empty($this->tipoProducto)){ ?>
								<div class="p-3 mb-3">
									<a class="btn btn-danger" href="<?=Router::get('tienda')?>">
										<i class="icon-remove"></i>
										Cancelar Filtrado
									</a>
								</div>
						<?php
							}
						?>
						<!-- ./Cancelar Filtrado -->
					</div>
					<div class="col-lg-10">
						<!-- Productos de la Tienda -->
						<div class="p-3">
							<h3 class="mb-3">
								<i class="icon-sort-by-attributes"></i>
								Productos de la Tienda
							</h3>
							<div class="row row-cols-1 row-cols-md-3 g-4 p-4">
								<?php
									foreach ($productos as $value) {
								?>
									<div class="col">
										<div class="card h-100 w-100" style="width: 18rem;">
										  <?=Asset::img('silueta_producto.png', ['class' => 'card-img-top', 'alt' => ' '])?>
										  <div class="card-body">
										    <h5 class="card-title"><?=$value['marca']?> (<?=$value['modelo']?>)</h5>
										    <p class="card-text">
										    	<b>Modelo</b> <span><?=$value['modelo']?></span> <br>
										    	<b>Talla</b> <span><?=$value['talla']?></span> <br>
										    	<b>Tipo</b> <span><?=$value['tipo']?></span> <br>
										    	<b>Precio</b> <span>$<?=number_format($value['precio'], 2, '.', '')?></span>
										    </p>
										    <a href="<?=Router::get('comprar', array('id' => $value['id_producto']))?>"
										       class="btn btn-success w-100 mb-2 btn-lg">Comprar</a>
										    <button 
										    	onclick="guardarEnCarrito('<?=$value['id_producto']?>')" 
										    	class="btn btn-primary w-100 btn-lg">
										    	Agregar al Carrito
										   	</button>
										  </div>
										</div>
									</div>
								<?php
									}
								?>
							</div>
						</div>
						<!-- ./Productos de la Tienda -->
					</div>
				</div>
			</div>
			<!-- ./Contenido del Sitio -->
		</div>

		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<?=Asset::js('tienda.js')?>
	</body>
</html>