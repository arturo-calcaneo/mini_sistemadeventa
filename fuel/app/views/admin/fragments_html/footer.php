		</div>
		<!-- Footer -->
	    <!-- Javascripts -->
	    <?=Asset::js('jquery-ui.min.js')?>
	    <?=Asset::js('modernizr.min.js')?>
	    <?=Asset::js('application-985b892b.js')?>
	    <!-- Stylesheets DataTables -->
	    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.3/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.6/sb-1.3.3/sp-2.0.1/sl-1.4.0/sr-1.1.1/datatables.min.css"/>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.3/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.6/sb-1.3.3/sp-2.0.1/sl-1.4.0/sr-1.1.1/datatables.min.js"></script>

		<script type="text/javascript">
		  $('#btn__salir').click(function(){
		    swal.fire({
		      icon: 'warning',
		      title: '¿Está seguro que desea salir?',
		      confirmButtonText: 'Continuar',
		      showCancelButton: true,
		      cancelButtonText: 'Cancelar'
		    }).then(function(res){
		      if(res.value){
		        window.location= '<?=Router::get('salir')?>';
		      }
		    });
		  });
		</script>
		<?=Asset::js('admin.js')?>
	</body>
</html>