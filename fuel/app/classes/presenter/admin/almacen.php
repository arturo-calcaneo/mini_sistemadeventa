<?php

class Presenter_Admin_Almacen extends Presenter
{
	public function view()
	{
		// Llamar al procedimiento para ver el almacen
		$this->proveedores= \DB::select()->from('proveedor')->execute();

		$this->productos= \DB::select()->from('productos')->execute();

		$this->almacen= \DB::query('call VerAlmacen()', DB::SELECT)->execute();
	}
}