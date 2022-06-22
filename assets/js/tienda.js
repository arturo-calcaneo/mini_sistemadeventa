'use strict'

$(document).ready(function(){
	var conteocarrito= parseInt(obtenerConteoCarrito());

	$('#conteo_carrito').text(conteocarrito);

	/**
	 *	Vaciar carrito si ya se ha generado la compra de este mismo
	**/
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);

	if(urlParams.get('aditional') != null){
		if(urlParams.get('aditional') == 'cartpurchased'){
			localStorage.removeItem('carrito');
			$('#conteo_carrito').text(obtenerConteoCarrito());
		}
	}
});

function logoutAccount(destURL){
	swal.fire({
		icon: 'warning',
		title: '¿Está seguro que desea salir?',
		confirmButtonText: 'Continuar',
		showCancelButton: true,
		cancelButtonText: 'Cancelar'
	}).then(function(res){
		if(res.value){
			window.location= destURL;
		}
	});

	return;
}

var productos= [];

function guardarEnCarrito(_productoId){
	if(localStorage.getItem('carrito') !== null){
		productos= JSON.parse(localStorage.getItem('carrito'));
	}

	if(!existeProducto_Aumentar(_productoId)){
		productos.push({ productoId: _productoId, cantidad: 1 });
	}

	localStorage.setItem('carrito', JSON.stringify(productos));

	return location.reload();
}

function existeProducto_Aumentar(_productoId){
	for(const producto of productos){
		if(producto.productoId == _productoId){
			producto.cantidad += 1;
			return true;
		}
	}

	return false;
}

function obtenerConteoCarrito(){
	var count= 0;

	if(localStorage.getItem('carrito') !== null){
		productos= JSON.parse(localStorage.getItem('carrito'));

		for(const producto of productos){
			count+= producto.cantidad;
		}
	}
	
	return count;
}

function obtenerDataCarrito(){
	return rawurlencode(b64EncodeUnicode(localStorage.getItem('carrito')));
}

function rawurlencode(str){
  str = (str + '')
  
  return encodeURIComponent(str)
    .replace(/!/g, '%21')
    .replace(/'/g, '%27')
    .replace(/\(/g, '%28')
    .replace(/\)/g, '%29')
    .replace(/\*/g, '%2A')
}

function b64EncodeUnicode(str){
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}