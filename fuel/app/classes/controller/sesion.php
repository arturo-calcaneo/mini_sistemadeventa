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

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Sesion extends Controller
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		return Response::forge(View::forge('sesion/index'));
	}

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  void
	 */
	public function post_index()
	{
		$correo= trim( Input::post('correo') );
		$contra= Auth::instance()->hash_password( Input::post('contra') );

		// Verificar si el usuario existe en la tabla de Vendedores
		$existsInVendedor= DB::select()->from('vendedor')
									   ->where('correo','=',$correo)
									   ->where('contra','=',$contra)
									   ->execute();

		$existVendedor= boolval( DB::count_last_query() );

		if(!$existVendedor){
			// Sino, verificar que el usuario exista en la tabla de clientes
			$existsInClientes= DB::select()->from('clientes')
										   ->where('correo','=',$correo)
									       ->where('contra','=',$contra)
										   ->execute();

			$existClient= boolval( DB::count_last_query() );

			if($existClient){
				$existsInClientes= $existsInClientes->as_array()[0];
				Session::set( 'user-logged', array_merge($existsInClientes, ['type' => 'cliente']) );

				// Si, si existe en clientes..
				Response::redirect('tienda');
			}else{
				// Si no existe en Vendedor ni en Clientes.. redirijir al Login.
				Response::redirect('/?message=dne_or_hnbc');
			}
		}else{
			$existsInVendedor= $existsInVendedor->as_array()[0];
			Session::set( 'user-logged', array_merge($existsInVendedor, ['type' => 'vendedor']) );

			// Si, si existe en vendedores..
			Response::redirect('dashboard');
		}
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('sesion/404'), 404);
	}
}
