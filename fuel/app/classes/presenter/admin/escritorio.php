<?php

class Presenter_Admin_Escritorio extends Presenter
{
	public function view()
	{
		/**
		 *	CANTIDAD DE CLIENTES EN EL SISTEMA
		**/
		$this->cantidadClientes= DB::query('call CantidadClientes()', DB::SELECT)->execute();
								 DB::instance()->disconnect();
		$this->cantidadClientes= $this->cantidadClientes->as_array()[0]['cantidad_clientes'];

		/**
		 *	CANTIDAD DE PRODUCTOS DISPONIBLES EN EL SISTEMA.
		**/
		$this->productosDisponibles= DB::query('call ProductosDisponibles()', DB::SELECT)->execute();
									 DB::instance()->disconnect();
		$this->productosDisponibles= $this->productosDisponibles->as_array()[0]['productos_disponibles'];

		/**
		 *	CANTIDAD DE PRODUCTOS VENDIDOS EN EL SISTEMA.
		**/
		$this->productosVendidos= DB::query('call ProductosVendidos()', DB::SELECT)->execute();
								  DB::instance()->disconnect();
		$this->productosVendidos= $this->productosVendidos->as_array()[0]['productos_vendidos'];
		
		/**
		 *	CANTIDAD DE PRODUCTOS COMPRADOS EN EL SISTEMA.
		**/
		$this->productosComprados= DB::query('call ProductosComprados()', DB::SELECT)->execute();
								   DB::instance()->disconnect();
		$this->productosComprados= $this->productosComprados->as_array()[0]['productos_comprados'];

		$query='SELECT * FROM ventas v
				JOIN clientes c ON c.id_clientes = v.id_cliente
				JOIN productos p ON p.id_producto = v.id_producto
				ORDER BY fecha_de_venta DESC';

		$this->ventas= DB::query($query)->execute();
					   DB::instance()->disconnect();

		$this->ventas= $this->ventas->as_array();

		$this->inversion= DB::query('call ObtenerInversion()', DB::SELECT)->execute();
		$this->inversion= $this->inversion->as_array()[0]['inversion_total'];

		$this->ganancias= DB::query('call ObtenerGanancias()', DB::SELECT)->execute();
		$this->ganancias= $this->ganancias->as_array()[0]['ganancia_total'];
	}
}