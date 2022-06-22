<?php

class Presenter_Admin_Productos extends Presenter
{
	public function view()
	{
		// Llamar al procedimiento para ver los productos
		$this->productos= \DB::query('call VerProductos()', DB::SELECT)->execute();
	}
}