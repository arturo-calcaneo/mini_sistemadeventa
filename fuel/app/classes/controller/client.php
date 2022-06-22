<?php

use Spipu\Html2Pdf\Html2Pdf;

class Controller_Client extends Controller
{
	public function before()
	{
		parent::before();

		// Validar que el usuario haya iniciado sesion con anterioridad
		if(Session::get('user-logged') == null){
			Response::redirect('/?message=hasnotbeenauth');
			exit;
		}else{
			if(is_array(Session::get('user-logged')) && Session::get('user-logged')['type'] != 'cliente'){
				Response::redirect('dashboard');
				exit;
			}
		}
	}

	public function action_carrito()
	{
		return Response::forge(Presenter::forge('client/carrito'));
	}

	public function action_carrito_comprar()
	{
		if($this->param('collection') !== null){
			$carritoInfo= rawurldecode(base64_decode($this->param('collection')));
			$carritoInfo= json_decode($carritoInfo, true);
			$info= [];

			foreach ($carritoInfo as $key => $value){
				$productoId= $value['productoId'];
				$clienteId=  intval(Session::get('user-logged')['id_clientes']);
				$cantidad= intval($value['cantidad']);

				$this->comprar($productoId, $clienteId, $cantidad);
				$info[]= ['id_producto' => $productoId, 'id_cliente' => $clienteId, 'cantidad' => $cantidad];
			}

			Response::redirect('tienda?message=purchased&aditional=cartpurchased&info=' . rawurlencode( base64_encode( serialize($info) ) ) );
		}else{
			Response::redirect('carrito');
		}
	}

	public function action_tienda()
	{
		$presenterTienda= Presenter::forge('client/tienda');
		$presenterTienda->tipoProducto= null;
		$presenterTienda->porTalla= null;

		// Comprar un producto
		if($this->param('id') !== null && !empty($this->param('id')))
		{
			$productoId= intval($this->param('id'));
			$clienteId=  intval(Session::get('user-logged')['id_clientes']);

			$this->comprar($productoId, $clienteId);

			$info= [['id_producto' => $productoId, 'id_cliente' => $clienteId, 'cantidad' => 1]];
			Response::redirect('tienda?message=purchased&info=' . rawurlencode( base64_encode( serialize($info) ) ) );
		}

		// Si el parametro de la url es (tipo)
		if( $this->param('tipo') !== null && !empty($this->param('tipo')) )
		{
			$presenterTienda->tipoProducto= $this->param('tipo');
		}

		return Response::forge($presenterTienda);
	}

	private function comprar($idProducto, $idCliente, $cantidad= 1){
		DB::query("call ComprarProducto($idProducto,$idCliente,$cantidad)")
			->execute();
	}

	public function action_recibo()
	{
		$response= new Response();

		$response->set_header('Content-Type', 'application/pdf');

		//Permite descargar directamente el recibo (.pdf)
		$response->set_header('Content-Disposition', 'attachment; filename="recibo.pdf"');

		// Set no cache
		$response->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
		$response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
		$response->set_header('Pragma', 'no-cache');

		$response->set_header('Content-Language', 'es');

		$info= unserialize( base64_decode( rawurldecode( $this->param('data') ) ) );

		$idCliente= $info[0]['id_cliente'];

		$datosCliente= DB::query('SELECT * FROM clientes WHERE id_clientes='.$idCliente)->execute();
		$datosCliente= $datosCliente->as_array();

		$nombreCliente= $datosCliente[0]['nombre'];
		$correoCliente= $datosCliente[0]['correo'];

		$html= '<div style="width:100%;text-align:center">
					<h3 style="margin-bottom:0">Zapateria Veracruz</h3>
					<small>Fracc. Villa Rica 1108</small> <br>
					<small>Katy Ripoll de Melo</small> <br>
				</div> <br>
				<div style="width:100%;font-size:90%">
					<b>Fecha</b> <span>'.date('Y-m-d').'</span> <br>
					<b>Cliente</b> <span>'.$nombreCliente.'</span> <br>
					<b>Correo</b> <span>'.$correoCliente.'</span>
				</div> <br>
				<table>
					<thead>
						<tr>
							<th>CANT.</th>
							<th>PRODUCTO</th>
							<th>PRECIO UNITARIO</th>
							<th>IMPORTE</th>
						</tr>
					</thead>
					<tbody>';

		$importe= '';
		$importeTotal= 0;

		foreach ($info as $value){
			$datosProducto= DB::query('SELECT * FROM productos WHERE id_producto='.$value['id_producto'])->execute();
			$datosProducto= $datosProducto->as_array()[0];

			$cantidad= $value['cantidad'];
			$importe= number_format(floatval($datosProducto['precio']) * intval($cantidad), 2,'.','');
			$importeTotal+= floatval($datosProducto['precio']) * intval($cantidad);

			$html.= '
				<tr>
					<td>'.$cantidad.'</td>
					<td>'.$datosProducto['marca'].' ('.$datosProducto['modelo'].')</td>
					<td>'.number_format(floatval($datosProducto['precio']), 2,'.','').'</td>
					<td>'.$importe.'</td>
				</tr>
			';
		}

		$html.= '
				<tr>
					<td style="border-left:0;border-right:0;border-bottom:0"></td>
					<td style="border-left:0;border-bottom:0"></td>
					<td><b>Total</b></td>
					<td>'.number_format($importeTotal,2,'.','').'</td>
				</tr>
			</tbody>
		</table> <hr>
		<h5>CONDICIONES Y FORMA DE PAGO</h5>
		<small>Será sujeto a devolución si el producto no ha sido manipulado intencionadamente y si se encuentra dentro del plazo de 30 dias.</small> <br><br>

		<small><b>Plazo de Devolución:</b> 30 días</small> <br>
		<small><b>Método de Pago:</b> el de preferencia</small>

		<style>
			table{
				border-collapse: collapse;
				font-size: 77%;
				width:100%;
				text-align:center;
			}

			table tr th,
			table tr td{
				width:25%;
				border:1px solid black;
			}
		</style>
		';

		/**
		 *	Datos para el recibo
		*/

		$pdf= new HTML2PDF('P','A6','es','true','UTF-8');

		$pdf->writeHTML($html);

		$pdf->Output('recibo.pdf');

		return $response;
	}
}