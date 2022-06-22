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

		<title>Carrito</title>
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

			<!-- Contenido del Sitio -->
			<div class="container-fluid">

				<!-- Boton Volver -->
				<a href="<?=Router::get('tienda')?>" class="btn btn-lg btn-secondary">
					<i class="icon-chevron-sign-left"></i>
					Volver
				</a> <br><br>
				<!-- ./Boton Volver -->

				<div class="container-fluid">
					<h3>Hay (<span id="conteo_carrito"></span>) elementos en el carrito.</h3>

					<div id="elementos_carrito" class="row row-cols-1 row-cols-md-3 g-3 mt-1 mb-2"></div>

					<div id="botones_carrito"></div>
				</div>
			</div>
			<!-- ./Contenido del Sitio -->
		</div>

		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<?=Asset::js('tienda.js')?>
		<script type="text/javascript">
			$(document).ready(function(){
				const conteocarrito= parseInt(obtenerConteoCarrito());
				const urlCarritoComprar= '<?=Router::get('carrito_comprar', ['collection'=> null])?>';

				if(conteocarrito > 0){

					//Mostrar Elementos del Carrito
					obtenerElementosCarrito();

					// Mostra Botones del Carrito
					$('#botones_carrito').html(`
						<a href="${urlCarritoComprar}${obtenerDataCarrito()}" class="btn btn-warning mt-3">Comprar Ahora</a>
						<button 
							class="btn btn-outline-danger mt-3" 
							onclick="localStorage.removeItem('carrito'); window.location= '<?=Router::get('tienda')?>';"
						>Cancelar Carrito</button>
					`);
				}
			});

			function obtenerElementosCarrito(){
				$.ajax({
					url: `<?=Router::get('obtenerproductos', ['collection' => null])?>${rawurlencode(b64EncodeUnicode(JSON.stringify(productos)))}`,
					method: 'GET',
					data: productos
				}).done(function(res){
					for(const producto of JSON.parse(res)){
						$('#elementos_carrito').append(`
							<div class="col">
								<div class="card w-100" style="width: 18rem;">
								  <div class="card-body">
								    <h5 class="card-title">${producto.marca } (${producto.modelo })</h5>
								    <h6 class="card-subtitle mb-2 text-muted">${producto.modelo }</h6>
								    <p class="card-text">
								    	<b>Talla</b> <span>${producto.talla }</span> <br>
								    	<b>Tipo</b> <span>${producto.tipo }</span> <br>
								    	<b>Precio</b> <span>$${producto.precio }</span>
								    </p>
								  </div>
								</div>
							</div>
						`);
					}
				});
			}
		</script>
	</body>
</html>