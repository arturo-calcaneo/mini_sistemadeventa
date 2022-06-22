<!-- Navbar -->
<div class='navbar navbar-default' id='navbar'>
  <a class='navbar-brand' href='#'>
    <i class="icon-adn"></i>
    SysSales
  </a>
  <ul class='nav navbar-nav pull-right'>
    <li class='dropdown user'>
      <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
        <i class='icon-user'></i>
        <strong><?=Session::get('user-logged')['correo']?></strong>
        <b class='caret'></b>
      </a>
      <ul class='dropdown-menu'>
        <li>
          <a href="#" id="btn__salir"><i class='icon-signout'></i> Salir</a>
        </li>
      </ul>
    </li>
  </ul>
</div>

<div id='wrapper'>