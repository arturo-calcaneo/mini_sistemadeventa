<?php

use Spipu\Html2Pdf\Html2Pdf;

class Controller_Admin extends Controller
{
    private $header;
    private $footer;

    private $navbar;
    private $sidebar;
    private $tools;

    public function before(){
        
        parent::before();

        // Verificar que se haya iniciado sesion correctamente..
        if(Session::get('user-logged') == null){
            Response::redirect('/?message=hasnotbeenauth');
            exit;
        }else{
            if(is_array(Session::get('user-logged')) && Session::get('user-logged')['type'] != 'vendedor'){
                Response::redirect('tienda');
                exit;
            }
        }
        
        $this->header= View::forge('admin/fragments_html/header');
        $this->footer= View::forge('admin/fragments_html/footer');
        
        $this->navbar=  View::forge('admin/fragments/navbar');
        $this->sidebar= View::forge('admin/fragments/sidebar');
        $this->sidebar->optionSelected= $this->obtenerMenuSeleccionado();

        $this->tools=   View::forge('admin/fragments/tools');
    }

    private function obtenerMenuSeleccionado()
    {
        return array(
            'inicio'    => Request::active()->route->name == 'dashboard' ? 'active' : '',
            'productos' => Request::active()->route->name == 'dashboard/productos' ? 'active' : '',
            'almacen'   => Request::active()->route->name == 'dashboard/almacen' ? 'active' : '',
            'proveedores' => Request::active()->route->name == 'dashboard/proveedores' ? 'active' :''
        );
    }

    /**
     * Escritorio: Pagina Principal
     *
     * @access  public
     * @return  View: footer
     */
    public function action_escritorio()
    {
        $this->getHeaderHTML('Escritorio | Administrador', 'Escritorio');
        
        echo Response::forge(Presenter::forge('admin/escritorio'));

        return $this->getFooterHTML();
    }

    /**
     * Productos | Pagina de la lista de Productos
     *
     * @access  public
     * @return  View: footer
     */
    public function action_productos()
    {
        $this->getHeaderHTML('Productos | Administrador', 'Productos');

        echo Response::forge(Presenter::forge('admin/productos'));

        return $this->getFooterHTML();
    }

    public function action_reporteproducto()
    {
        $response= $this->responseConfigToPDF('productos');

        $pdf= new HTML2PDF('P','A4','es','true','UTF-8');

        $productos= DB::select()->from('productos')->order_by('id_producto','desc')->execute();
        $productos= $productos->as_array();

        $fecha= date('Y-m-d');
        $nr= count($productos);

        $html= <<<HTML
            <div style="width: 100%; text-align: center">
                <h1 style="margin-bottom: 0">Zapateria Veracruz</h1>
                <h3>Lista de Productos</h3>
            </div>
            <div style="width: 100%; text-align: left">
                <h4 style="margin-bottom: 0">Fecha: <span style="font-weight: lighter">{$fecha}</span></h4>
                <h4>Numero de Registros: <span style="font-weight: lighter">{$nr}</span></h4>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Precio</th>
                        <th>Talla</th>
                        <th>Tipo</th>
                        <th>En Stock</th>
                    </tr>
                </thead>
                <tbody>
        HTML;

        foreach($productos as $value){
            $html.= <<<HTML
                <tr>
                    <td>{$value['id_producto']}</td>
                    <td>{$value['marca']}</td>
                    <td>{$value['modelo']}</td>
                    <td><b>$</b>{$value['precio']}</td>
                    <td>{$value['talla']}</td>
                    <td>{$value['tipo']}</td>
                    <td>{$value['stock']}</td>
                </tr>
            HTML;
        }

        $html.= <<<HTML
                </tbody>
            </table>
            <style type="text/css">
                table{ border:1px solid #bfbfbf; border-collapse: collapse; width: 100% }

                table tr th,
                table tr td{ border:1px solid #bfbfbf; vertical-align: top; width: 14.2% }

                table tr th{ padding: 8px }
                table tr td{ word-break: break-word; padding: 5px }
            </style>
        HTML;

        $pdf->writeHTML($html);
        $pdf->Output($response['nombre_del_archivo']);

        return $response['response'];
    }

    public function action_reportealmacen()
    {
        $response= $this->responseConfigToPDF('almacen');

        $pdf= new HTML2PDF('P','A4','es','true','UTF-8');

        $almacen= DB::query('call VerAlmacen()', DB::SELECT)->execute();
        $almacen= $almacen->as_array();

        $fecha= date('Y-m-d');
        $nr= count($almacen);

        $html= <<<HTML
            <div style="width: 100%; text-align: center">
                <h1 style="margin-bottom: 0">Zapateria Veracruz</h1>
                <h3>Reporte de Almacen</h3>
            </div>
            <div style="width: 100%; text-align: left">
                <h4 style="margin-bottom: 0">Fecha: <span style="font-weight: lighter">{$fecha}</span></h4>
                <h4>Numero de Registros: <span style="font-weight: lighter">{$nr}</span></h4>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Proveedor</th>
                        <th>Cantidad Disponible</th>
                    </tr>
                </thead>
                <tbody>
        HTML;

        $count= 1;

        foreach($almacen as $value){
            $html.= <<<HTML
                <tr>
                    <td>{$count}</td>
                    <td>{$value['producto']}</td>
                    <td>{$value['proveedor']}</td>
                    <td>{$value['cantidad']}</td>
                </tr>
            HTML;

            $count++;
        }

        $html.= <<<HTML
                </tbody>
            </table>
            <style type="text/css">
                table{ border:1px solid #bfbfbf; border-collapse: collapse; width: 100% }

                table tr th,
                table tr td{ border:1px solid #bfbfbf; vertical-align: top; width: 25% }

                table tr th{ padding: 8px }
                table tr td{ word-break: break-word; padding: 5px }
            </style>
        HTML;

        $pdf->writeHTML($html);
        $pdf->Output($response['nombre_del_archivo']);

        return $response['response'];
    }

    public function action_reporteproveedores()
    {
        $response= $this->responseConfigToPDF('proveedores');

        $pdf= new HTML2PDF('P','A4','es','true','UTF-8');

        $proveedores= DB::select()->from('proveedor')->execute();
        $proveedores= $proveedores->as_array();

        $fecha= date('Y-m-d');
        $nr= count($proveedores);

        $html= <<<HTML
            <div style="width: 100%; text-align: center">
                <h1 style="margin-bottom: 0">Zapateria Veracruz</h1>
                <h3>Lista de Proveedores</h3>
            </div>
            <div style="width: 100%; text-align: left">
                <h4 style="margin-bottom: 0">Fecha: <span style="font-weight: lighter">{$fecha}</span></h4>
                <h4>Numero de Registros: <span style="font-weight: lighter">{$nr}</span></h4>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                    </tr>
                </thead>
                <tbody>
        HTML;

        foreach($proveedores as $value){
            $html.= <<<HTML
                <tr>
                    <td>{$value['id_proveedor']}</td>
                    <td>{$value['nombre']}</td>
                    <td>{$value['telefono']}</td>
                    <td>{$value['correo']}</td>
                    <td>{$value['direccion']}</td>
                </tr>
            HTML;
        }

        $html.= <<<HTML
                </tbody>
            </table>
            <style type="text/css">
                table{ border:1px solid #bfbfbf; border-collapse: collapse; width: 100% }

                table tr th,
                table tr td{ border:1px solid #bfbfbf; vertical-align: top; width: 20% }

                table tr th{ padding: 8px }
                table tr td{ word-break: break-word; padding: 5px }
            </style>
        HTML;

        $pdf->writeHTML($html);
        $pdf->Output($response['nombre_del_archivo']);

        return $response['response'];
    }

    public function action_reporteventas_xdia()
    {
        $response= $this->responseConfigToPDF('ventas_xdia');

        $fecha= $this->param('xdia');

        $ventas= DB::query("call VentasXDia('$fecha')", DB::SELECT)->execute();
        $ventas= $ventas->as_array();

        $pdf= new HTML2PDF('P','A4','es','true','UTF-8');

        $nr= count($ventas);

        $header= <<<HTML
            <div style="width: 100%; text-align: center">
                <h1 style="margin-bottom: 0">Zapateria Veracruz</h1>
                <h3>Movimiento de Ventas</h3>
            </div>
            <div style="width: 100%; text-align: left">
                <h4 style="margin-bottom: 0">Tipo: <span style="font-weight: lighter">Por Día</span></h4>
                <h4 style="margin-bottom: 0">Fecha: <span style="font-weight: lighter">{$fecha}</span></h4>
                <h4>Numero de Registros: <span style="font-weight: lighter">{$nr}</span></h4>
            </div>
        HTML;

        $pdf->writeHTML($this->getHTMLVentasPDF($header,$ventas));
        $pdf->Output($response['nombre_del_archivo']);

        return $response['response'];
    }

    public function action_reporteventas_xsemana()
    {
        $response= $this->responseConfigToPDF('ventas_xsemana');

        $anio= intval( date('Y', strtotime($this->param('xsemana'))) );
        $semana= intval( date('W', strtotime($this->param('xsemana'))) );

        $d= $this->getStartAndEndDate($semana,$anio);

        $ventas= DB::query("call VentasXSemana('$d[start_date]','$d[end_date]')", DB::SELECT)->execute();
        $ventas= $ventas->as_array();

        $nr= count($ventas);

        $header= <<<HTML
            <div style="width: 100%; text-align: center">
                <h1 style="margin-bottom: 0">Zapateria Veracruz</h1>
                <h3>Movimiento de Ventas</h3>
            </div>
            <div style="width: 100%; text-align: left">
                <h4 style="margin-bottom: 0">Tipo: <span style="font-weight: lighter">Por Semana</span></h4>
                <h4 style="margin-bottom: 0">Fecha Inicio: <span style="font-weight: lighter">{$d['start_date']}</span></h4>
                <h4 style="margin-bottom: 0">Fecha Final: <span style="font-weight: lighter">{$d['end_date']}</span></h4>
                <h4>Numero de Registros: <span style="font-weight: lighter">{$nr}</span></h4>
            </div>
        HTML;

        $pdf= new HTML2PDF('P','A4','es','true','UTF-8');

        $pdf->writeHTML($this->getHTMLVentasPDF($header,$ventas));

        $pdf->Output($response['nombre_del_archivo']);

        return $response['response'];
    }

    public function action_reporteventas_xmes()
    {
        $fecha= date('Y-m-01', strtotime($this->param('xmes')));
        
        $response= $this->responseConfigToPDF('ventas_xmes');

        $ventas= DB::query("call VentasXMes('$fecha')", DB::SELECT)->execute();
        $ventas= $ventas->as_array();

        $pdf= new HTML2PDF('P','A4','es','true','UTF-8');

        $nr= count($ventas);
        $fecha= date('Y-M', strtotime($fecha));

        $header= <<<HTML
            <div style="width: 100%; text-align: center">
                <h1 style="margin-bottom: 0">Zapateria Veracruz</h1>
                <h3>Movimiento de Ventas</h3>
            </div>
            <div style="width: 100%; text-align: left">
                <h4 style="margin-bottom: 0">Tipo: <span style="font-weight: lighter">Por Mes</span></h4>
                <h4 style="margin-bottom: 0">Fecha: <span style="font-weight: lighter">{$fecha}</span></h4>
                <h4>Numero de Registros: <span style="font-weight: lighter">{$nr}</span></h4>
            </div>
        HTML;

        $pdf->writeHTML($this->getHTMLVentasPDF($header,$ventas));

        $pdf->Output($response['nombre_del_archivo']);

        return $response['response'];
    }

    private function responseConfigToPDF($filename_, $prefijo='zapateriaVeracruz_')
    {
        $response= new Response();

        $response->set_header('Content-Type','application/pdf');

        $nombreArchivo= $prefijo . $filename_ . '_' . date("Y_m_d_H.i.s_") . '.pdf';

        $response->set_header('Content-Disposition','attachment; filename="'.$nombreArchivo.'"');

        // Set no cache
        $response->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
        $response->set_header('Pragma', 'no-cache');

        $response->set_header('Content-Language', 'es');

        return ['nombre_del_archivo' => $nombreArchivo, 'response' => $response];
    }

    private function getStartAndEndDate($week, $year){
      $dateTime = new DateTime();

      $dateTime->setISODate($year, $week);

      $result['start_date'] = $dateTime->format('Y-m-d');
      $dateTime->modify('+6 days');
      $result['end_date'] = $dateTime->format('Y-m-d');

      return $result;
    }

    private function getHTMLVentasPDF($header, $ventas)
    {
        $html= <<<HTML
            {$header}
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Fecha de Venta</th>
                    </tr>
                </thead>
                <tbody>
        HTML;

        foreach($ventas as $value){
            $html.= <<<HTML
                <tr>
                    <td>{$value['id_venta']}</td>
                    <td>{$value['nombre_cliente']}</td>
                    <td>{$value['nombre_producto']}</td>
                    <td>{$value['cantidad']}</td>
                    <td>{$value['precio']}</td>
                    <td>{$value['fecha_de_venta']}</td>
                </tr>
            HTML;
        }

        $html.= <<<HTML
                </tbody>
            </table>
            <style type="text/css">
                table{ border:1px solid #bfbfbf; border-collapse: collapse; width: 100% }

                table tr th,
                table tr td{ border:1px solid #bfbfbf; vertical-align: top; width: 16.6% }

                table tr th{ padding: 8px }
                table tr td{ padding: 5px }
            </style>
        HTML;

        return $html;
    }

    /**
     * Almacen | Pagina del Almacen
     *
     * @access  public
     * @return  View: footer
     */
    public function action_almacen()
    {
        $this->getHeaderHTML('Almacen | Administrador', 'Almacen');

        echo Response::forge(Presenter::forge('admin/almacen'));

        return $this->getFooterHTML();
    }

    /**
     * Proveedores | Pagina de la lista de Proveedores
     *
     * @access  public
     * @return  View: footer
     */
    public function action_proveedores()
    {
        $this->getHeaderHTML('Proveedores | Administrador', 'Proveedores');

        echo Response::forge(Presenter::forge('admin/proveedores'));

        return $this->getFooterHTML();
    }

    /**
     * Fragmentos de Precargas HTML
     *
     * @access  private
     * @return  View
     */
    private function getHeaderHTML($titulo, $tituloC= '')
    {
             $this->header->titulo= $titulo;
        echo $this->header;

        echo $this->navbar;
        echo $this->sidebar;

             $this->tools->tituloContenido= $tituloC;
        echo $this->tools;
    }

    private function getFooterHTML()
    {
        return $this->footer;
    }
}