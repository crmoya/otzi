/**
 * jQuery script for adding new content from template field
 *
 * NOTE!
 * This script depends on jquery.format.js
 *
 * IMPORTANT!
 * Do not change anything except specific commands!
 */

function dv(T) {
	var M = 0, S = 1; for (; T; T = Math.floor(T / 10))
		S = (S + T % 10 * (9 - M++ % 6)) % 11; return S ? S - 1 : 'k';
}

jQuery(document).ready(function () {



	$(document.body).on('change', '.factura', function (e) {
		var i = $(this).attr('i');
		$('#errorNumero' + i).html('');
		var factura = $(this).val();
		if (!isNaN(factura) && factura > 0) {
			var numero = $.trim($("#numero" + i).val());
			if (numero == '') {
				$('#errorNumero' + i).html('Debe ingresar un valor');
				$('#numero' + i).removeAttr('readonly');
			}
		}
	});

	$(document.body).on('change', '.rut_rinde', function (e) {
		$(this).val(replaceAll($(this).val(), ".", ""));
	});

	$(document.body).on('change', '.rut_rindeR', function (e) {
		$(this).val(replaceAll($(this).val(), ".", ""));
	});


	$('.factura').each(function (e) {
		var i = $(this).attr('i');
		$('#errorNumero' + i).html('');
		var factura = $(this).val();
		if (!isNaN(factura) && factura > 0) {
			var numero = $.trim($("#numero" + i).val());
			if (numero == '') {
				$('#errorNumero' + i).html('Debe ingresar un valor');
				$('#numero' + i).removeAttr('readonly');
			}
		}
	});

	$(document.body).on('change', '.nroRendicion', function (e) {
		var i = $(this).attr('i');
		var numero = $.trim($(this).val());
		if (numero != "") {
			$('#errorNumero' + i).html('');
		}
		else {
			var factura = $("#factura" + i).val();
			if (!isNaN(factura) && factura > 0) {
				$('#errorNumero' + i).html('Debe ingresar un valor');
			}
		}
	});

	$('.guiaOp').each(function (e) {
		var i = $(this).attr('i');
		var guia = $(this).val();
		if (!isNaN(guia) && guia > 0) {
			$('#factura' + i).removeAttr("readonly");
		}
	});

	var isPanne = $("#checkPanne").is(':checked');
	if (!isPanne)
		$("#panne").hide();

	$("#checkPanne").click(function () {
		if (!isPanne)
			$("#panne").fadeIn();
		else
			$("#panne").fadeOut();
		isPanne = !isPanne;
	});

	hideEmptyHeaders();
	$(".add").click(function () {
		var template = jQuery.format(jQuery.trim($(this).siblings(".template").val()));
		var place = $(this).parents(".templateFrame:first").children(".templateTarget");
		var i = place.find(".rowIndex").length > 0 ? place.find(".rowIndex").max() + 1 : 0;
		$(template(i)).appendTo(place);
		place.siblings('.templateHead').show();
		// start specific commands

		// end specific commands
	});

	$(".add").click(function () {
		if ($(this).attr("tipo") == "expedicion") {
			nExpediciones++;
		}
		if ($(this).attr("tipo") == "combustible") {
			nCombustibles++;
		}
		if ($(this).attr("tipo") == "repuesto") {
			nRepuestos++;
		}
	});
	$(document.body).on('click', '.remove', function (e) {
		$(this).parents(".templateContent:first").remove();
		hideEmptyHeaders();
		$(this).attr("validate", "false");
	});

	if (history.forward(1)) {
		location.replace(history.forward(1));
	}

	$(document.body).on('change', '.fixedCoeficiente', function (e) {

		var id = $(this).attr('id');
		var i = id.substring(id.length - 1);
		$('#errorCoeficiente' + i).text('');
		var text = $(this).val();
		var num = 0;
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			num = new Number(text);
			num = num.toFixed(0);
			$(this).val(num);
		} else {
			$(this).val(0);
			$('#errorCoeficiente' + i).text('Coeficiente entre 1 y 100');
			return;
		}
		if (num < 1 || num > 100) {
			$('#errorCoeficiente' + i).text('Coeficiente entre 1 y 100');
			$(this).val(0);
			return;
		}

		var capacidad = $("#capacidad").val();
		var nVueltas = $('#nVueltas' + i).val();
		if (capacidad == null) {
			$('#totalTransportado' + i).val(0);
			$('#total' + i).val(0);
			return;
		}

		var tr = capacidad * nVueltas;
		tr = tr.toFixed(2);
		$("#totalTransportado" + i).val(tr);

		var totalTr = tr * num / 100;
		totalTr = totalTr.toFixed(2);
		$("#totalTransportado" + i).val(totalTr);
		var pu = $("#pu" + i).attr("pu");
		var km = $("#kmRecorridos" + i).val();
		var total = totalTr * pu * km;
		total = total.toFixed(2);
		$("#total" + i).val(total);

	});

	$(document.body).on('change', '.fixedTotalTransportado', function (e) {
		var text = $(this).val();
		var num = 0;
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}
		var id = $(this).attr('id');
		var i = id.substring(id.length - 1);
		var pu = $("#pu" + i).attr("pu");
		var km = $("#kmRecorridos" + i).val();
		var total = num * pu * km;
		total = total.toFixed(2);
		$("#total" + i).val(total);

	});

	$('.fixedInicial').change(function () {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}

		var valorInicial = $(this).val();
		var valorFinal = $(".fixedFinal").val();
		var valor = valorFinal - valorInicial;
		valor = new Number(valor);
		$("#kmRecorridos").text(valor.toFixed(2));
	});

	$(document.body).on('change', '.fixedFinal', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}

		var valorFinal = $(this).val();
		var valorInicial = $(".fixedInicial").val();
		var valor = valorFinal - valorInicial;
		valor = new Number(valor);
		$("#kmRecorridos").text(valor.toFixed(2));
	});

	$(document.body).on('change', '.fixedHInicial', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}
		var valorInicial = $(this).val();
		var valorFinal = $("#hFinal").val();
		var valor = (valorFinal - valorInicial).toFixed(2);
		$("#REquipoPropio_horas").val(valor);
		$("#REquipoArrendado_horas").val(valor);
	});

	$(document.body).on('change', '.fixedHFinal', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}
		var valorFinal = $(this).val();
		var valorInicial = $("#hInicial").val();
		var valor = (valorFinal - valorInicial).toFixed(2);
		console.log(valor);
		$("#REquipoPropio_horas").val(valor);
		$("#REquipoArrendado_horas").val(valor);
	});

	$(document.body).on('change', '.fixedPetroleoLts', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}

		var id = $(this).attr("id");
		var i = id.substring(id.length - 1);

		var lts = $(this).val();
		var valor = $("#valorTotal" + i).val();
		var precio = $("#precioUnitario" + i).val();

		if ((valor == "" || valor == 0) && (precio == "" || precio == 0)) {
			return;
		}
		if ((valor == "" || valor == 0) && (precio != "" || precio != 0)) {
			var calculo = lts * precio;
			calculo = calculo.toFixed(2);
			$("#valorTotal" + i).val(calculo);
		}
		if ((valor != "" || valor != 0) && (precio == "" || precio == 0)) {
			if (lts != 0) {
				var calculo = valor / lts;
				calculo = calculo.toFixed(2);
				$("#precioUnitario" + i).val(calculo);
			}
			else {
				$("#precioUnitario" + i).val(0);
			}
		}
		if ((valor != "" || valor != 0) && (precio != "" || precio != 0)) {
			var calculo = lts * precio;
			calculo = calculo.toFixed(2);
			$("#valorTotal" + i).val(calculo);
		}

	});

	$(document.body).on('change', '.fixedPrecio', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = parseInt(num.toFixed(0));
			$(this).val(num);
		} else {
			$(this).val(0);
		}

		var id = $(this).attr("id");
		var i = id.substring(id.length - 1);

		var precio = $(this).val();
		var valor = $("#valorTotal" + i).val();
		var lts = $("#petroleoLts" + i).val();

		if ((valor == "" || valor == 0) && (lts == "" || lts == 0)) {
			return;
		}
		if ((valor == "" || valor == 0) && (lts != "" || lts != 0)) {
			var calculo = lts * precio;
			calculo = calculo.toFixed(2);
			$("#valorTotal" + i).val(calculo);
		}
		if ((valor != "" || valor != 0) && (lts == "" || lts == 0)) {
			if (precio != 0) {
				var calculo = valor / precio;
				calculo = calculo.toFixed(2);
				$("#petroleoLts" + i).val(calculo);
			}
			else {
				$("#petroleoLts" + i).val(0);
			}
		}
		if ((valor != "" || valor != 0) && (lts != "" || lts != 0)) {
			var calculo = lts * precio;
			calculo = calculo.toFixed(2);
			$("#valorTotal" + i).val(calculo);
		}
	});

	$(document.body).on('change', '.fixedValor', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}

		var id = $(this).attr("id");
		var i = id.substring(id.length - 1);

		var valor = $(this).val();
		var lts = $("#petroleoLts" + i).val();
		var precio = $("#precioUnitario" + i).val();

		if ((lts == "" || lts == 0) && (precio == "" || precio == 0)) {
			return;
		}
		if ((lts == "" || lts == 0) && (precio != "" || precio != 0)) {
			if (precio != 0) {
				var calculo = valor / precio;
				calculo = calculo.toFixed(2);
				$("#petroleoLts" + i).val(calculo);
			}
			else {
				$("#petroleoLts" + i).val(0);
			}
		}
		if ((lts != "" || lts != 0) && (precio == "" || precio == 0)) {
			if (lts != 0) {
				var calculo = valor / lts;
				calculo = calculo.toFixed(2);
				$("#precioUnitario" + i).val(calculo);
			}
			else {
				$("#precioUnitario" + i).val(0);
			}
		}
		if ((lts != "" || lts != 0) && (precio != "" || precio != 0)) {
			if (precio != 0) {
				var calculo = valor / precio;
				calculo = calculo.toFixed(2);
				$("#petroleoLts" + i).val(calculo);
			}
			else {
				$("#petroleoLts" + i).val(0);
			}
		}

	});


	$(document.body).on('change', '.fixed', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(2);
			$(this).val(num);
		} else {
			$(this).val(0);
		}
	});

	$(document.body).on('focus', '.fecha', function (e) {
		$('.fecha').datepicker($.datepicker.regional["es"]);
	});

	$(document.body).on('click', '.add', function (e) {
		$('.fecha').datepicker($.datepicker.regional["es"]);
	});

	$(document.body).on('change', '.fixedInt', function (e) {
		var text = $(this).val();
		text = text.replace(',', '.');
		if (!isNaN(text)) {
			var num = new Number(text);
			num = num.toFixed(0);
			$(this).val(num);
		} else {
			$(this).val(0);
		}
	});

	$(document.body).on('change', '.nVueltas', function (e) {
		var id = $(this).attr("id");
		var i = id.substring(id.length - 1);

		var capacidad = $("#capacidad").val();
		if (capacidad == null) {
			$('#totalTransportado' + i).val(0);
			$('#total' + i).val(0);
			return;
		}
		var coef = $('#coeficiente' + i).val();
		var nVueltas = $(this).val();
		var valor = capacidad * nVueltas * coef / 100;
		valor = valor.toFixed(2);
		$("#totalTransportado" + i).val(valor);

		var pu = $("#pu" + i).attr("pu");
		var km = $("#kmRecorridos" + i).val();
		var total = valor * pu * km;
		total = total.toFixed(2);
		$("#total" + i).val(total);
	});


	$(".camion").change(function () {
		for (i = 0; i < nExpediciones; i++) {
			$('#nVueltas' + i).val(0);
			$('#totalTransportado' + i).val(0);
			$('#total' + i).val(0);
		}
	});

	$(document.body).on('change', '.origenDestino', function (e) {
		var id = $(this).attr('id');
		var i = id.substring(id.length - 1);
		var pu = $("#" + id).val();
		$("#pu" + i).attr("pu", pu);
	});




});

function getFecha(fecha) {
	if (fecha != null) {
		var fixed = fecha.split("/");
		if (fixed.length == 3) {
			if (!isNaN(fixed[2]) && !isNaN(fixed[1]) && !isNaN(fixed[0])) {
				return new Date(fixed[2] + "-" + fixed[1] + "-" + fixed[0]);
			}
			else {
				return null;
			}
		} else {
			return null;
		}
	}
}



function checkCompareFechas() {
	$("#errorFecha").attr("style", "display:none;");
	var fFin = getFecha($('.fecha_final').val());
	var fIni = getFecha($('.fecha_inicio').val());
	if (fFin != null && fIni != null) {
		var diasDiff = (fFin.getTime() - fIni.getTime()) / (1000 * 60 * 60 * 24);
		if (diasDiff <= -1 || diasDiff > 93) {
			$("#errorFecha").html("Fechas no pueden diferir por más de 3 meses.");
			$("#errorFecha").attr("style", "display:inline;");
			return false;
		}

	}
	else {
		return false;
	}
	return true;
}

var nExpediciones = 0;
var nCombustibles = 0;
var nRepuestos = 0;

function checkPanne() {
	$("#errorPanne").html('');
	var iniPanne = $(".iniPanne").val();
	var finPanne = $(".finPanne").val();
	iniPanne = iniPanne.replace(":", "");
	finPanne = finPanne.replace(":", "");

	var revisar = $("#checkPanne").is(':checked');
	if (iniPanne >= finPanne && revisar) {
		$("#errorPanne").css("color", "red");
		$("#errorPanne").html('Error: Hora Fin debe ser mayor a Hora Inicio.');
		return false;
	}

	return true;
}

function replaceAll(text, busca, reemplaza) {
	try {
		if (text != "") {
			while (text.toString().indexOf(busca) != -1)
				text = text.toString().replace(busca, reemplaza);
		}
		return text;
	} catch (e) { return ""; }
}

function checkNumero() {
	var valid = true;
	$('.errorNumero').each(function (e) {
		var val = $(this).html();
		if (val != "") {
			valid = false;
		}
	});
	return valid;
}

function checkNumeroCom() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#numero" + i).val();
			if (valor != "") {
				if (!is_int(valor)) {
					$("#numero" + i).css('background', 'pink');
					$("#errorNumero" + i).html("Error: Debe ser un número entero");
					return false;
				}
			}
			else {
				$("#numero" + i).css('background', 'white');
				$("#errorNumero" + i).html("");
			}
		}
	}
	return true;
}

function checkNumeroRep() {
	for (i = 0; i < nRepuestos; i++) {
		if ($("#removeRepuesto" + i).attr("validate") == "true") {
			var valor = $("#numeroRep" + i).val();
			if (valor != "") {
				if (!is_int(valor)) {
					$("#numeroRep" + i).css('background', 'pink');
					$("#errorNumeroRep" + i).html("Error: Debe ser un número entero");
					return false;
				}
			}
			else {
				$("#numeroRep" + i).css('background', 'white');
				$("#errorNumeroRep" + i).html("");
			}
		}
	}
	return true;
}

function checkExpediciones() {

	var expReales = 0;

	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			expReales++;
		}
	}
	if (expReales == 0) {
		alert('Por favor ingrese al menos una expedición.');
		return false;
	} else {
		return true;
	}
}

function checkKmFinal() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#kmFinal" + i).val();
			if (!is_decimal(valor)) {
				$("#kmFinal" + i).css('background', 'pink');
				$("#errorKmFinal" + i).html("Error: Debe ser un número decimal");
				return false;
			}
			if (valor < 0) {
				$("#kmFinal" + i).css('background', 'pink');
				$("#errorKmFinal" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}


function checkRepuesto() {
	for (i = 0; i < nRepuestos; i++) {
		if ($("#removeRepuesto" + i).attr("validate") == "true") {
			var valor = $("#repuesto" + i).val();
			if (valor == "") {
				$("#repuesto" + i).css('background', 'pink');
				$("#errorRepuesto" + i).html("Error: No puede ser blanco");
				return false;
			}
			else {
				$("#repuesto" + i).css('background', 'white');
				$("#errorRepuesto" + i).html("");
			}
		}
	}
	return true;
}

function checkRutProveedor() {
	for (i = 0; i < nRepuestos; i++) {
		if ($("#removeRepuesto" + i).attr("validate") == "true") {
			var valor = $("#rut_proveedorR" + i).val();
			if (valor == "") {
				$("#rut_proveedorR" + i).css('background', 'pink');
				$("#errorRutProveedorR" + i).html("Error: No puede ser blanco");
				return false;
			}
			else {
				valor = replaceAll(valor, '.', '');
				var valorArr = valor.split('-');
				if (valorArr.length == 2) {
					var rut = valorArr[0];
					var digito = valorArr[1];
					digito = digito.toLowerCase();
					if (digito != dv(rut)) {
						$("#rut_proveedorR" + i).css('background', 'pink');
						$("#errorRutProveedorR" + i).html("Error: el rut no es válido.");
						return false;
					}
					else {
						$("#rut_proveedorR" + i).css('background', 'white');
						$("#errorRutProveedorR" + i).html("");
					}
				}
				else {
					$("#rut_proveedorR" + i).css('background', 'pink');
					$("#errorRutProveedorR" + i).html("Error: el rut no es válido.");
					return false;
				}
			}
		}
	}
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#rut_proveedor" + i).val();
			if (valor == "") {
				$("#rut_proveedor" + i).css('background', 'pink');
				$("#errorRutProveedor" + i).html("Error: No puede ser blanco");
				return false;
			}
			else {
				valor = replaceAll(valor, '.', '');
				var valorArr = valor.split('-');
				if (valorArr.length == 2) {
					var rut = valorArr[0];
					var digito = valorArr[1];
					digito = digito.toLowerCase();
					if (digito != dv(rut)) {
						$("#rut_proveedor" + i).css('background', 'pink');
						$("#errorRutProveedor" + i).html("Error: el rut no es válido.");
						return false;
					}
					else {
						$("#rut_proveedor" + i).css('background', 'white');
						$("#errorRutProveedor" + i).html("");
					}
				}
				else {
					$("#rut_proveedor" + i).css('background', 'pink');
					$("#errorRutProveedor" + i).html("Error: el rut no es válido.");
					return false;
				}
			}
		}
	}
	return true;
}

function checkNombreProveedor() {
	for (i = 0; i < nRepuestos; i++) {
		if ($("#removeRepuesto" + i).attr("validate") == "true") {
			var valor = $("#nombre_proveedorR" + i).val();
			if (valor == "") {
				$("#nombre_proveedorR" + i).css('background', 'pink');
				$("#errorNombreProveedorR" + i).html("Error: No puede ser blanco");
				return false;
			}
			else {
				$("#nombre_proveedorR" + i).css('background', 'white');
				$("#errorNombreProveedorR" + i).html("");
			}
		}
	}
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#nombre_proveedor" + i).val();
			if (valor == "") {
				$("#nombre_proveedor" + i).css('background', 'pink');
				$("#errorNombreProveedor" + i).html("Error: No puede ser blanco");
				return false;
			}
			else {
				$("#nombre_proveedor" + i).css('background', 'white');
				$("#errorNombreProveedor" + i).html("");
			}
		}
	}
	return true;
}


function checkMontoNeto() {
	for (i = 0; i < nRepuestos; i++) {
		if ($("#removeRepuesto" + i).attr("validate") == "true") {
			var valor = $("#montoNeto" + i).val();
			if (valor == "") {
				$("#montoNeto" + i).css('background', 'pink');
				$("#errorMontoNeto" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_int(valor)) {
				$("#montoNeto" + i).css('background', 'pink');
				$("#errorMontoNeto" + i).html("Error: Debe ser un número entero");
				return false;
			}
			/*if(valor<0){
				$("#montoNeto"+i).css('background','pink');
				$("#errorMontoNeto"+i).html("Error: No puede ser negativo");
				return false;	
			}*/
		}
	}
	return true;
}
function checkKmRecorridos() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#kmRecorridos" + i).val();
			if (valor == "") {
				$("#kmRecorridos" + i).css('background', 'pink');
				$("#errorKmRecorridos" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_decimal(valor)) {
				$("#kmRecorridos" + i).css('background', 'pink');
				$("#errorKmRecorridos" + i).html("Error: Debe ser un decimal");
				return false;
			} else {
				if (valor < 0) {
					$("#kmRecorridos" + i).css('background', 'pink');
					$("#errorKmRecorridos" + i).html("Error: Debe ser positivo");
					return false;
				}
			}
		}
	}
	return true;
}

function checkTotal() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#total" + i).val();
			if (valor == "") {
				$("#total" + i).css('background', 'pink');
				$("#errorTotal" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_decimal(valor)) {
				$("#total" + i).css('background', 'pink');
				$("#errorTotal" + i).html("Error: Debe ser un decimal");
				return false;
			}
			if (valor < 0) {
				$("#total" + i).css('background', 'pink');
				$("#errorTotal" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}

function checkTotalTransportado() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#totalTransportado" + i).val();
			if (valor == "") {
				$("#totalTransportado" + i).css('background', 'pink');
				$("#errorTotalTransportado" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_decimal(valor)) {
				$("#totalTransportado" + i).css('background', 'pink');
				$("#errorTotalTransportado" + i).html("Error: Debe ser un decimal");
				return false;
			}
			if (valor < 0) {
				$("#totalTransportado" + i).css('background', 'pink');
				$("#errorTotalTransportado" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}

function checkKmGps() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#kmGps" + i).val();
			if (!is_decimal(valor)) {
				$("#kmGps" + i).css('background', 'pink');
				$("#errorKmGps" + i).html("Error: Debe ser un decimal");
				return false;
			}
			if (valor < 0) {
				$("#kmGps" + i).css('background', 'pink');
				$("#errorKmGps" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}

function checkValorTotal() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#valorTotal" + i).val();
			if (valor == "") {
				$("#valorTotal" + i).css('background', 'pink');
				$("#errorValorTotal" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_decimal(valor)) {
				$("#valorTotal" + i).css('background', 'pink');
				$("#errorValorTotal" + i).html("Error: Debe ser un decimal");
				return false;
			}
		}
	}
	return true;
}


function checkPrecioUnitario() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#precioUnitario" + i).val();
			if (valor == "") {
				$("#precioUnitario" + i).css('background', 'pink');
				$("#errorPrecioUnitario" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_int(valor)) {
				$("#precioUnitario" + i).css('background', 'pink');
				$("#errorPrecioUnitario" + i).html("Error: Debe ser un entero");
				return false;
			}
			if (valor < 0) {
				$("#precioUnitario" + i).css('background', 'pink');
				$("#errorPrecioUnitario" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}

function checkKmInicial() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#kmInicial" + i).val();
			if (!is_decimal(valor)) {
				$("#kmInicial" + i).css('background', 'pink');
				$("#errorKmInicial" + i).html("Error: Debe ser un decimal");
				return false;
			}
			if (valor < 0) {
				$("#kmInicial" + i).css('background', 'pink');
				$("#errorKmInicial" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}

function checkHInicial() {
	var valor = $("#hInicial").val();
	if (valor < 0) {
		$("#REquipoPropio_hInicial_em_").html("Debe ser mayor que 0");
		$("#REquipoPropio_hInicial_em_").show();
		$("#REquipoArrendado_hInicial_em_").html("Debe ser mayor que 0");
		$("#REquipoArrendado_hInicial_em_").show();
		return false;
	}
	return true;
}
function checkHFinal() {
	var valor = $("#hFinal").val();
	if (valor < 0) {
		$("#REquipoPropio_hFinal_em_").html("Debe ser mayor que 0");
		$("#REquipoPropio_hFinal_em_").show();
		$("#REquipoArrendado_hFinal_em_").html("Debe ser mayor que 0");
		$("#REquipoArrendado_hFinal_em_").show();
		return false;
	}
	return true;
}

function checkPetroleoLts() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#petroleoLts" + i).val();
			if (valor == "") {
				$("#petroleoLts" + i).css('background', 'pink');
				$("#errorPetroleoLts" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_decimal(valor)) {
				$("#petroleoLts" + i).css('background', 'pink');
				$("#errorPetroleoLts" + i).html("Error: Debe ser un decimal");
				return false;
			}
		}
	}
	return true;
}

function checkKmCarguio() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#kmCarguio" + i).val();
			/*if(valor == ""){
				$("#kmCarguio"+i).css('background','pink');
				$("#errorKmCarguio"+i).html("Error: No puede ser blanco");
				return false;	
			}*/
			if (!is_decimal(valor)) {
				$("#kmCarguio" + i).css('background', 'pink');
				$("#errorKmCarguio" + i).html("Error: Debe ser un decimal");
				return false;
			}
			if (valor < 0) {
				$("#kmCarguio" + i).css('background', 'pink');
				$("#errorKmCarguio" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}

function checkNVueltas() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#nVueltas" + i).val();
			if (valor == "") {
				$("#nVueltas" + i).css('background', 'pink');
				$("#errorNVueltas" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_int(valor)) {
				$("#nVueltas" + i).css('background', 'pink');
				$("#errorNVueltas" + i).html("Error: Debe ser número");
				return false;
			}
			if (valor < 0) {
				$("#nVueltas" + i).css('background', 'pink');
				$("#errorNVueltas" + i).html("Error: No puede ser negativo");
				return false;
			}
		}
	}
	return true;
}


function is_decimal(value) {
	if (isNaN(value)) {
		return false;
	}
	var s = value + "";
	if (s.length > 13) {
		return false;
	}
	if (!isNaN(value)) {
		return true;
	} else {
		return false;
	}
}

function is_int(value) {
	if (isNaN(value)) {
		return false;
	}
	var s = value + "";
	if (s.length > 11) {
		return false;
	}
	if ((parseFloat(value) == parseInt(value)) && !isNaN(value)) {
		return true;
	} else {
		return false;
	}
}

function checkChofer() {
	var valor = $("#chofer_id").val();
	if (valor == "") {
		$("#chofer_id" + i).css('background', 'pink');
		$("#errorChofer_id").html("Error: Seleccione un Chofer");
		return false;
	}
	else {
		$("#chofer_id" + i).css('background', 'white');
		$("#errorChofer_id" + i).html("");
	}
	return true;
}

function checkTipoCombustible() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#tipoCombustible" + i).val();
			if (valor == "") {
				$("#tipoCombustible" + i).css('background', 'pink');
				$("#errorTipoCombustible" + i).html("Error: Seleccione un Tipo Combustible");
				return false;
			}
			else {
				$("#tipoCombustible" + i).css('background', 'white');
				$("#errorTipoCombustible" + i).html("");
			}
		}
	}
	return true;
}

function checkSupervisor() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#supervisor" + i).val();
			if (valor == "") {
				$("#supervisor" + i).css('background', 'pink');
				$("#errorSupervisor" + i).html("Error: Seleccione un Supervisor");
				return false;
			}
			else {
				$("#supervisor" + i).css('background', 'white');
				$("#errorSupervisor" + i).html("");
			}
		}
	}
	return true;
}

function checkGuia() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valorFactura = $("#factura" + i).val();
			if (valorFactura == "") {
				$("#factura" + i).css('background', 'pink');
				$("#errorFactura" + i).html("Error: Debe ingresar valor para Factura o Boleta");
				return false;
			}
			else {
				$("#factura" + i).css('background', 'white');
				$("#errorFactura" + i).html("");
				return true;
			}
		}
	}
	return true;
}

function checkFaena() {
	for (i = 0; i < nExpediciones; i++) {
		if ($("#removeExpedicion" + i).attr("validate") == "true") {
			var valor = $("#faena_id" + i).val();
			if (valor == "") {
				$("#faena_id" + i).css('background', 'pink');
				$("#errorFaena_id" + i).html("Error: Seleccione un Faena");
				return false;
			}
			else {
				$("#faena_id" + i).css('background', 'white');
				$("#errorFaena_id" + i).html("");
			}
		}
	}
	return true;
}

function checkFaenaComb() {
	for (i = 0; i < nCombustibles; i++) {
		if ($("#removeCombustible" + i).attr("validate") == "true") {
			var valor = $("#faenaC_id" + i).val();
			if (valor == "") {
				$("#faenaC_id" + i).css('background', 'pink');
				$("#errorFaenaC_id" + i).html("Error: Seleccione un Faena");
				return false;
			}
			else {
				$("#faenaC_id" + i).css('background', 'white');
				$("#errorFaenaC_id" + i).html("");
			}
		}
	}
	return true;
}

function checkFaenaRep() {
	for (i = 0; i < nRepuestos; i++) {
		if ($("#removeRepuesto" + i).attr("validate") == "true") {
			var valor = $("#faenaR_id" + i).val();
			if (valor == "") {
				$("#faenaR_id" + i).css('background', 'pink');
				$("#errorFaenaR_id" + i).html("Error: Seleccione un Faena");
				return false;
			}
			else {
				$("#faenaR_id" + i).css('background', 'white');
				$("#errorFaenaR_id" + i).html();
			}
		}
	}
	return true;
}

function checkCantidad() {
	for (i = 0; i < nRepuestos; i++) {
		if ($("#removeRepuesto" + i).attr("validate") == "true") {
			var valor = $("#cantidad" + i).val();
			if (valor == "") {
				$("#cantidad" + i).css('background', 'pink');
				$("#errorCantidad" + i).html("Error: No puede ser blanco");
				return false;
			}
			if (!is_int(valor)) {
				$("#cantidad" + i).css('background', 'pink');
				$("#errorCantidad" + i).html("Error: Debe ser un número entero");
				return false;
			}
			if (valor < 0) {
				$("#cantidad" + i).css('background', 'pink');
				$("#errorCantidad" + i).html("Error: No puede ser negativo");
				return false;
			}
			$("#cantidad" + i).css('background', 'white');
			$("#errorCantidad" + i).html("");
		}
	}
	return true;
}

function checkHorasArrendado() {
	var valor = $("#REquipoArrendado_horas").val();
	if (valor < 0) {
		$("#REquipoArrendado_horas_em_").html("Debe ser mayor que 0");
		$("#REquipoArrendado_horas_em_").show();
		return false;
	}
	else {
		$("#REquipoArrendado_horas_em_").html("");
		$("#REquipoArrendado_horas_em_").show();
	}

	return true;
}
function checkHorasPropio() {
	var valor = $("#REquipoPropio_horas").val();
	if (valor < 0) {
		$("#REquipoPropio_horas_em_").html("Debe ser mayor que 0");
		$("#REquipoPropio_horas_em_").show();
		return false;
	}
	else {
		$("#REquipoPropio_horas_em_").html("");
		$("#REquipoPropio_horas_em_").show();
	}

	return true;
}

function hideEmptyHeaders() {
	$('.templateTarget').filter(function () { return $.trim($(this).text()) === '' }).siblings('.templateHead').hide();
}

