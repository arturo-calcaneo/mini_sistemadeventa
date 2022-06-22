'use strict'

var baseUrlReporteVentas= '';
var urlFormateadaReporteVentas= '';

var buscarPor= '';

$(document).ready(function(){
	// Obtener URL de Boton de Generar Reporte
	baseUrlReporteVentas= $('#link_download_reportev').attr('href');
});

function eventChkbx_dia(){
	var tableRows= $('#filtrado_reporte_ventas tr');

	// Uncheckear checkbox de semana y mes
	$(tableRows[0]).find('input[type="checkbox"]').prop('checked',true);
	$(tableRows[1]).find('input[type="checkbox"]').prop('checked',false);
	$(tableRows[2]).find('input[type="checkbox"]').prop('checked',false);
	// Deshabilitar input de semana y mes
	$(tableRows[0]).find('#input_dia').prop('disabled', false);
	$(tableRows[1]).find('#input_semana').prop('disabled', true);
	$(tableRows[2]).find('#select_mes').prop('disabled', true);

	urlFormateadaReporteVentas= baseUrlReporteVentas + '/dia/';
	buscarPor= 'dia';
}

function eventChkbx_semana(){
	var tableRows= $('#filtrado_reporte_ventas tr');

	// Uncheckear checkbox de dia y mes
	$(tableRows[0]).find('input[type="checkbox"]').prop('checked',false);
	$(tableRows[1]).find('input[type="checkbox"]').prop('checked',true);
	$(tableRows[2]).find('input[type="checkbox"]').prop('checked',false);
	// Deshabilitar input de dia y mes
	$(tableRows[0]).find('#input_dia').prop('disabled', true);
	$(tableRows[1]).find('#input_semana').prop('disabled', false);
	$(tableRows[2]).find('#select_mes').prop('disabled', true);

	urlFormateadaReporteVentas= baseUrlReporteVentas + '/semana/';
	buscarPor= 'semana';
}

function eventChkbx_mes(){
	var tableRows= $('#filtrado_reporte_ventas tr');

	// Uncheckear checkbox de dia y semana
	$(tableRows[0]).find('input[type="checkbox"]').prop('checked',false);
	$(tableRows[1]).find('input[type="checkbox"]').prop('checked',false);
	$(tableRows[2]).find('input[type="checkbox"]').prop('checked',true);
	// Deshabilitar input de dia y semana
	$(tableRows[0]).find('#input_dia').prop('disabled', true);
	$(tableRows[1]).find('#input_semana').prop('disabled', true);
	$(tableRows[2]).find('#select_mes').prop('disabled', false);

	urlFormateadaReporteVentas= baseUrlReporteVentas + '/mes/';
	buscarPor= 'mes';
}

$('.btn-reporte-ventas').click(function(e){
	e.preventDefault();

	// Cuando se presione Generar Reporte, establecer variables por default.
	urlFormateadaReporteVentas= baseUrlReporteVentas + '/dia/';
	buscarPor= 'dia';

	swal.fire({
		html: `
			<h3 style='font-size: 3.15rem;font-weight:600'>
				<i class='icon-file-text'></i>
				Reporte de Ventas
			</h3> <br>
			<table style='width:100%' id='filtrado_reporte_ventas'>
				<tr>
					<td style='text-align:right; padding-right: 1rem'>
						<label style='font-weight:600;font-size:1.75rem'>Por Dia</label>
					</td>
					<td style='text-align:left; padding-right: 1rem'>
						<input type='date' id='input_dia' class='form-control'>
					</td>
					<td style='text-align:left'>
						<input type='checkbox' onclick='eventChkbx_dia()' style='width:20px;height:20px' checked>
					</td>
				</tr>
				<tr>
					<td style='text-align:right; padding-right: 1rem; padding-top: 1rem'>
						<label style='font-weight:600;font-size:1.75rem'>Por Semana</label>
					</td>
					<td style='text-align:left; padding-right: 1rem; padding-top: 1rem'>
						<input type='week' id='input_semana' class='form-control' disabled>
					</td>
					<td style='text-align:left; padding-top: 1rem'>
						<input type='checkbox' onclick='eventChkbx_semana()' style='width:20px;height:20px' id='chkbx_semana'>
					</td>
				</tr>
				<tr>
					<td style='text-align:right; padding-right: 1rem; padding-top: 1rem'>
						<label style='font-weight:600;font-size:1.75rem'>Por Mes</label>
					</td>
					<td style='text-align:left; padding-right: 1rem; padding-top: 1rem'>
						<select id='select_mes' class='form-control' disabled>
							<option value=''>Seleccione una opcion:</option>
							<option value='january'>Enero</option>
							<option value='february'>Febrero</option>
							<option value='march'>Marzo</option>
							<option value='april'>Abril</option>
							<option value='may'>Mayo</option>
							<option value='june'>Junio</option>
							<option value='july'>Julio</option>
							<option value='august'>Agosto</option>
							<option value='september'>Septiembre</option>
							<option value='october'>Octubre</option>
							<option value='november'>Noviembre</option>
							<option value='december'>Diciembre</option>
						</select>
					</td>
					<td style='text-align:left; padding-top: 1rem'>
						<input type='checkbox' onclick='eventChkbx_mes()' style='width:20px;height:20px' id='chkbx_mes'>
					</td>
				</tr>
			</table>
		`,
		confirmButtonText: 'Continuar',
		showCancelButton: true,
		cancelButtonText: 'Cancelar'
	}).then(function(res){
		if(res.value){
			// Obtener el valor del input determinado
			if(buscarPor == 'dia'){
				urlFormateadaReporteVentas += obtenerValor('#input_dia');
			}else if(buscarPor == 'semana'){
				urlFormateadaReporteVentas += obtenerValor('#input_semana');
			}else{
				urlFormateadaReporteVentas += obtenerValor('#select_mes');
			}

			$('#link_download_reportev').attr('href', urlFormateadaReporteVentas);
			$('#link_download_reportev')[0].click();
		}
	});
});

function obtenerValor(selector){
	return $(selector).val().length > 0 ? $(selector).val() : null;
}