
<!-- Sidebar -->
<section id='sidebar'>
  <i class='icon-align-justify icon-large' id='toggle'></i>
  <ul id='dock'>
    <li class='<?=$optionSelected['inicio']?> launcher'>
      <i class="icon-home"></i>
      <?=Html::anchor(Router::get('dashboard'), 'Inicio')?>
    </li>
    <li class='<?=$optionSelected['productos']?> launcher dropdown hover'>
      <i class="icon-th"></i>
      <?=Html::anchor(Router::get('dashboard/productos'), 'Productos')?>
      <ul class="dropdown-menu" style="display: none;">
        <li>
          <a href="<?=Router::get('dashboard/productos')?>">
            <i class="icon-plus"></i> 
            Nuevo Producto
          </a>
        </li>
        <li>
          <a href="<?=Router::get('reportedeproducto')?>">
            <i class="icon-file-text"></i> 
            Generar Reporte
          </a>
        </li>
      </ul>
    </li>
    <li class='<?=$optionSelected['almacen']?> launcher dropdown hover'>
      <i class="icon-folder-open"></i>
      <?=Html::anchor(Router::get('dashboard/almacen'), 'Almacen')?>
      <ul class="dropdown-menu" style="display: none;">
        <li>
          <a href="<?=Router::get('dashboard/almacen')?>">
            <i class="icon-plus"></i> 
            Nueva Compra
          </a>
        </li>
        <li>
          <a href="<?=Router::get('reportedealmacen')?>">
            <i class="icon-file-text"></i> 
            Generar Reporte
          </a>
        </li>
      </ul>
    </li>
    <li class='<?=$optionSelected['proveedores']?> launcher dropdown hover'>
      <i class="icon-th-list"></i>
      <?=Html::anchor(Router::get('dashboard/proveedores'), 'Proveedores')?>
      <ul class="dropdown-menu" style="display: none;">
        <li>
          <a href="<?=Router::get('dashboard/proveedores')?>">
            <i class="icon-plus"></i> 
            Nuevo Proveedor
          </a>
        </li>
        <li>
          <a href="<?=Router::get('reportedeproveedor')?>">
            <i class="icon-file-text"></i> 
            Generar Reporte
          </a>
        </li>
      </ul>
    </li>
    <li class='launcher'>
      <i class='icon-signout'></i>
      <a href="#" id="btn__salir">Salir</a>
    </li>
  </ul>
  <div data-toggle='tooltip' id='beaker' title='Made by lab2023'></div>
</section>