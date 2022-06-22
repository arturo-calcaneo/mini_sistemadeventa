<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.9-dev
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

return array(
	/**
	 * -------------------------------------------------------------------------
	 *  Default route
	 * -------------------------------------------------------------------------
	 *
	 */

	'_root_' => 'sesion/index',

	/**
	 * -------------------------------------------------------------------------
	 *  Page not found
	 * -------------------------------------------------------------------------
	 *
	 */

	'_404_' => 'sesion/404',

	/**
	 * -------------------------------------------------------------------------
	 *  Rest: Productos
	 * -------------------------------------------------------------------------
	 *
	 *  Una ruta para consultar una lista de productos en especifico.
	 *
	 */

	'obtenerproductos/:collection' => array(function(){
		$productos= Request::active()->param('collection');

		$productos= rawurldecode(base64_decode($productos));
		$productos= json_decode($productos, true);

		$listaProductos= [];

		foreach ($productos as $value) {
			$producto= DB::query('SELECT * FROM productos WHERE id_producto=' . $value['productoId'] . ' LIMIT 1')
						->execute();

			$listaProductos[]= $producto->as_array()[0];
		}

		echo json_encode($listaProductos);
	},
	'name' => 'obtenerproductos'),

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Tienda
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'tienda' => 'client/tienda',

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Carrito de Compras
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'carrito' => 'client/carrito',

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Tienda
	 *  Criterio: Comprar el Producto
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'carrito/comprar/:collection' => array('client/carrito_comprar','name' => 'carrito_comprar'),

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Tienda
	 *  Criterio: Comprar el Producto
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'tienda/comprar/(?P<id>\d+?)' => array('client/tienda','name' => 'comprar'),

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Tienda
	 *  Criterio: Mostrar solo por un Tipo
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'tienda/tipo/:tipo' => array('client/tienda', 'name' => 'tipo'),

	/**
	 *
	 *
	 *  A route for generate 'recibo' using Controller
	 *
	 */

	'recibo/:data' => array('client/recibo', 'name' => 'recibo'),

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Escritorio
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'dashboard' => 'admin/escritorio',

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Productos
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'dashboard/productos' => 'admin/productos',

	/**
	 * -------------------------------------------------------------------------
	 *  Reporte: Productos
	 * -------------------------------------------------------------------------
	 *
	 *  A route for generate a pdf
	 *
	 */

	'dashboard/productos/reporte' => array('admin/reporteproducto', 'name' => 'reportedeproducto'),

	/**
	 * -------------------------------------------------------------------------
	 *  Reporte: Proveedores
	 * -------------------------------------------------------------------------
	 *
	 *  A route for generate a pdf
	 *
	 */

	'dashboard/proveedores/reporte' => array('admin/reporteproveedores', 'name' => 'reportedeproveedor'),

	/**
	 * -------------------------------------------------------------------------
	 *  Reporte: Ventas
	 * -------------------------------------------------------------------------
	 *
	 *  A route for generate a pdf
	 *
	 */

	'dashboard/ventas/reporte' => array('admin/reporteventas', 'name' => 'reportedeventas'),

	'dashboard/ventas/reporte/dia/:xdia' => 'admin/reporteventas_xdia',

	'dashboard/ventas/reporte/semana/:xsemana' => 'admin/reporteventas_xsemana',

	'dashboard/ventas/reporte/mes/:xmes' => 'admin/reporteventas_xmes',

	/**
	 * -------------------------------------------------------------------------
	 *  Reporte: Almacen
	 * -------------------------------------------------------------------------
	 *
	 *  A route for generate a pdf
	 *
	 */

	'dashboard/almacen/reporte' => array('admin/reportealmacen', 'name' => 'reportedealmacen'),

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Almacen
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'dashboard/almacen' => 'admin/almacen',

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Recibo
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'dashboard/recibo' => 'admin/recibo',

	/**
	 * -------------------------------------------------------------------------
	 *  Presenter: Proveedores
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'dashboard/proveedores' => 'admin/proveedores',

	/**
	 * -------------------------------------------------------------------------
	 *  Salir
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'salir' => function(){
		Session::delete('user-logged');

		Session::destroy();

		Response::redirect('/');
	},


	/**
	 * -------------------------------------------------------------------------
	 *  POST
	 * -------------------------------------------------------------------------
	 *
	 *  Rutas (POST) para llevar a cabo los procesos de Insert, Update y Delete.
	 *
	 */
	'dashboard/nuevo-proveedor' => function(){
		if(isset($_POST)){
			DB::insert('proveedor')
				->set([
					'nombre' 	=> $_POST['nombre'],
					'telefono' 	=> $_POST['telefono'],
					'correo' 	=> $_POST['correo'],
					'direccion' => $_POST['direccion']
				])->execute();

			Response::redirect('dashboard/proveedores');
		}
	},

	'dashboard/nueva-compra' => function(){
		if(isset($_POST)){
			DB::insert('compras')
				->set([
					'cantidad' 	=> $_POST['cantidad'],
					'id_proveedor' 	=> $_POST['idProveedor'],
					'id_producto' 	=> $_POST['idProducto']
				])->execute();

			Response::redirect('dashboard/almacen');
		}
	},

	'dashboard/nuevo-producto' => function(){
		if(isset($_POST)){
			DB::insert('productos')
				->set([
					'marca' 	=> $_POST['marca'],
					'modelo' 	=> $_POST['modelo'],
					'precio' 	=> $_POST['precio'],
					'talla' 	=> $_POST['talla'],
					'tipo' 		=> $_POST['tipo'],
					'stock' 	=> '0'
				])->execute();

			Response::redirect('dashboard/productos');
		}
	}
);
