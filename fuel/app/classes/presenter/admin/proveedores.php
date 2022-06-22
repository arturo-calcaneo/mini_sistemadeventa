<?php

class Presenter_Admin_Proveedores extends Presenter
{
	public function view()
	{
		$this->proveedores= \DB::select()->from('proveedor')->execute();
	}
}