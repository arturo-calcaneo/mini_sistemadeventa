<?php

class Presenter_Client_Tienda extends Presenter
{
	public function view()
	{
		if($this->tipoProducto !== null && !empty($this->tipoProducto))
		{
			$this->productos= \DB::query("call VerProductosXTipo('{$this->tipoProducto}')", DB::SELECT)->execute();
		}else{
			$this->productos= \DB::query('call VerProductos()', DB::SELECT)->execute();
		}

		DB::instance()->disconnect();

		$this->categorias= \DB::query('call VerCategoria()', DB::SELECT)->execute();
						   DB::instance()->disconnect();

		$clienteId= Session::get('user-logged')['id_clientes'];
		$this->aplicaDescuento= \DB::query("call AplicaDescuento({$clienteId})", DB::SELECT)->execute();
								 DB::instance()->disconnect();

		$this->aplicaDescuento= $this->aplicaDescuento->as_array()[0]['aplica_descuento'];
		$this->aplicaDescuento= boolval($this->aplicaDescuento);
	}
}