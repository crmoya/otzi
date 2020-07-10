/**
 * jQuery script for adding new content from template field
 *
 * NOTE!
 * This script depends on jquery.format.js
 *
 * IMPORTANT!
 * Do not change anything except specific commands!
 */
jQuery(document).ready(function(){
	
	/*$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		dateFormat: 'dd/mm/yy', firstDay: 0,
        isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['es']);*/
 
        $(document).on('change','.upper',function(e){
		var str = $(this).val().toUpperCase();
		$(this).val(str);
	});
	
	$('.upper').change(function(e){
		var str = $(this).val().toUpperCase();
		$(this).val(str);
	});
	
	$('.upper').each(function(e){
		var str = $(this).val().toUpperCase();
		$(this).val(str);
	});
	
	hideEmptyHeaders();
	
	/*
	
	$(".add").click(function(){
		var template = jQuery.format(jQuery.trim($(this).siblings(".template").val()));
		var place = $(this).parents(".templateFrame:first").children(".templateTarget");
		var i = place.find(".rowIndex").length>0 ? place.find(".rowIndex").max()+1 : 0;
		
		$(template(i)).appendTo(place);
		place.siblings('.templateHead').show();
		// start specific commands

		// end specific commands
	});
	*/
	
	$(document).on('click','.remove',function(e){
		$(this).parents(".templateContent:first").remove();
		hideEmptyHeaders();
		$(this).attr("validate","false");
	});
	
	if (history.forward(1)){
		location.replace(history.forward(1));
	} 
		
        $(document).on('change','.fixed',function(e){        
		var text = $(this).val();
		text = text.replace(',','.');
		if(!isNaN(text)){
			var num = new Number(text);
			num = num.toFixed(0);
	   		$(this).val(num);
		}else{
			$(this).val(0);
		}
	});
	
        $(document).on('change','.fixedMayor0',function(e){
		var text = $(this).val();
		text = text.replace(',','.');
		if(!isNaN(text)){
			var num = new Number(text);
			if(num < 0){
				$(this).val(0);
			}else{
				num = num.toFixed(0);
		   		$(this).val(num);
			}
		}else{
			$(this).val(0);
		}
	});
	
        $(document).on('change','.fecha_inicio_obra',function(e){
		var f_inicial = getFecha($(this).val());
		var plazo = $('.plazo').val();
		calculaFechaFinal(f_inicial, plazo);
	});
	
        $(document).on('change','.plazo',function(e){
            var f_inicial = getFecha($('.fecha_inicio_obra').val());
            var plazo = $(this).val();
            calculaFechaFinal(f_inicial, plazo);
	});
        
        /*$(document).on('change','.plazoR',function(e){
            var f_inicial = getFecha($('.fecha_inicio_obra').val());
            var plazo = $(this).val();
            calculaFechaFinalR(f_inicial, plazo);
	});*/
        
	try{
		$('.plata').currency({ region: 'USD', thousands: '.', decimal: ',', decimals: 0 });
		$('.plata_sin').currency({ region: '', thousands: '.', decimal: ',', decimals: 0,hidePrefix:true });
		$('.plata_decimales_sin').currency({ region: '', thousands: '.', decimal: ',', decimals: 4,hidePrefix:true });
		
	}catch(e){}
	$('.dinero').css('text-align','right');
	
        $(document).on('change','.fixed2',function(e){
		var text = $(this).val();
		text = text.replace(',','.');
		if(!isNaN(text)){
			var num = new Number(text);
			num = num.toFixed(2);
	   		$(this).val(num);
		}else{
			$(this).val(0);
		}
	});
	
	$('.fecha_inicio').change(function(e){
		checkFechas(1);
	});
	
	$('.fecha_inicial').change(function(e){
		checkFechaInicio();
	});
		
});

var mesesResolucion = 0;
var mIni = 0;
var aIni = 0;

function escondeTodosFlujos(){
    $('.flujo').hide();
}
function escondeFlujos(){
    var fecha = $('.fecha_inicio').val();
    var fecha_arr = fecha.split('/');
    var m_actual = -1;
    var a_actual = -1;
    if(fecha_arr.length == 3){
        try{
            m_actual = parseInt(fecha_arr[1]);
            a_actual = parseInt(fecha_arr[2]);
        }catch(e){}
    }

    $('.flujo').each(function(){
        $(this).show();
        var mes = $(this).attr("mes");
        var agno = $(this).attr("agno");
        if(agno < a_actual){
            $(this).hide();
        }else if(agno == a_actual){
            if(mes < m_actual){
                    $(this).hide();
            }
        }
    });	
}

function escondeFlujosRes(){
    var fecha = $('.fecha_inicio').val();
    var fecha_arr = fecha.split('/');
    var m_actual = -1;
    var a_actual = -1;
    if(fecha_arr.length == 3){
        try{
            m_actual = parseInt(fecha_arr[1]);
            a_actual = parseInt(fecha_arr[2]);
        }catch(e){}
    }

    var fechaFin = $('.fecha_final').val();
    
    var fechaFin_arr = fechaFin.split('/');
    var m_fin = -1;
    var a_fin = -1;
    if(fechaFin_arr.length == 3){
        try{
            m_fin = parseInt(fechaFin_arr[1]);
            a_fin = parseInt(fechaFin_arr[2]);
        }catch(e){}
    }
    //console.log(fechaFin_arr);
    $('.flujo').each(function(){
        $(this).show();
        var mes = $(this).attr("mes");
        var agno = $(this).attr("agno");
        if(agno < a_actual){
            $(this).hide();
        }else if(agno == a_actual){
            if(mes < m_actual){
                    $(this).hide();
            }
        }
        if(agno > a_fin){
            $(this).hide();
        }
        else if(agno == a_fin){
            if(mes > m_fin){
                $(this).hide();
            }
        }
    });	
}

function calculaFechaFinal(f_inicial,plazo){
	var valor = "";
	if(f_inicial != null){
		valor = backFecha(new Date(f_inicial.getTime() + plazo * 24 * 60 * 60 * 1000));
	}
	$('.fecha_final').val(valor);
	setFlujosProgramados();
}

function calculaFechaFinalR(f_inicial,plazo){
	var valor = "";
	if(f_inicial != null){
		valor = backFecha(new Date(f_inicial.getTime() + plazo * 24 * 60 * 60 * 1000));
	}
	$('.fecha_final').val(valor);
	setFlujosProgramadosRes();
}

function setFlujosProgramadosRes(){
    escondeFlujosRes();
    var template = jQuery.format(jQuery.trim($(".add").siblings(".template").val()));
    var place = $(".add").parents(".templateFrame:first").children(".templateTarget");
    place.empty();
    
    //if(checkCompareFechas()){
    var fFin = getFecha($('.fecha_final').val());
    var fIni = getFecha($('.fecha_inicio_obra').val());

    if(fIni != null && fFin != null){
        mIni = ($('.fecha_inicio_obra').val().split("/")[1]);
        var mFin = ($('.fecha_final').val().split("/"))[1];
        aIni = ($('.fecha_inicio_obra').val().split("/"))[2];
        var aFin = ($('.fecha_final').val().split("/"))[2];
        var agnosDiff = aFin - aIni;
        var mesesDiff = mFin - mIni + 1 + 12*agnosDiff;
        var i = 0;
        for(j=i;j<mesesDiff;j++){
            //var i = place.find(".rowIndex").length>0 ? place.find(".rowIndex").max()+1 : 0;
            $(template(i)).appendTo(place);
            place.siblings('.templateHead').show();
            i++;
        };
        mesesResolucion = mesesDiff;
        labelMesesRes();
            //}		
    }
}


var mIngresados = 0;

function setFlujosProgramados(){
	var template = jQuery.format(jQuery.trim($(".add").siblings(".template").val()));
	var place = $(".add").parents(".templateFrame:first").children(".templateTarget");
	place.empty();
	
	//if(checkCompareFechas()){
		var fFin = getFecha($('.fecha_final').val());
		var fIni = getFecha($('.fecha_inicio_obra').val());
                
		if(fIni != null && fFin != null){
			if(mIni == 0){
				mIni = ($('.fecha_inicio_obra').val().split("/"))[1];
			}
			var mFin = ($('.fecha_final').val().split("/"))[1];
			if(aIni == 0){
				aIni = ($('.fecha_inicio_obra').val().split("/"))[2];
			}
			var aFin = ($('.fecha_final').val().split("/"))[2];
			
			var agnosDiff = aFin - aIni;
			var mesesDiff = mFin - mIni + 1 + 12*agnosDiff;
			
			var i = mIngresados;
			for(j=0;j<mesesDiff;j++){
				//var i = place.find(".rowIndex").length>0 ? place.find(".rowIndex").max()+1 : 0;
				$(template(i)).appendTo(place);
				place.siblings('.templateHead').show();
				i++;
			};
			mesesResolucion = mesesDiff;
			labelMeses();
		//}		
	}
}

function labelMeses(){
	var fin = new Number(mesesResolucion) + new Number(mIngresados);
	for(k=mIngresados;k<fin;k++){
		$("#mes"+k).val(labelMes((Number(mIni)+Number(k)-Number(mIngresados))%12));
		$("#agno"+k).val(labelAgno(k-Number(mIngresados)));
	}
}

function labelMesesRes(){
	var fin = new Number(mesesResolucion) + new Number(mIngresados);
	for(k=mIngresados;k<fin;k++){
		$("#mes"+k).val(labelMes((Number(mIni)+Number(k)-Number(mIngresados))%12));
		$("#agno"+k).val(labelAgno(k-Number(mIngresados)));
	}
}

function labelAgno(i)
{
	var mesActual = Number(mIni)+Number(i);
	var agnosExtra = Math.floor((mesActual-1)/12);
	return (Number(aIni)+Number(agnosExtra))+"";
}

function labelMes(mes){
	switch (mes) {	
	 	case 0:
        	return "Diciembre";
        case 1:
        	return "Enero";
        case 2:
        	return "Febrero";
        case 3:
        	return "Marzo";
        case 4:
        	return "Abril";
        case 5:
        	return "Mayo";
        case 6:
        	return "Junio";
        case 7:
        	return "Julio";
        case 8:
        	return "Agosto";
        case 9:
        	return "Septiembre";
        case 10:
        	return "Octubre";
        case 11:
        	return "Noviembre";
        case 12:
        	return "Diciembre";
        default:
            return "";
	}
}


function contains(array,x){
	for(i=0;i<array.length;i++){
		if(array[i]==x){
			return true;
		}
	}
	return false;
}

function replaceAll( text, busca, reemplaza ){
	try{
		if(text != ""){
		while (text.toString().indexOf(busca) != -1)
			text = text.toString().replace(busca,reemplaza);
		}
		return text;
	}catch(e){return "";}
}

function trim(myString)
{
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

function checkNotNull(total){
	for(i=0;i<total;i++){
		$("#errorNotNull"+i).attr("style","display:none;");
		var valor = $(".not_null"+i).val();
		if(trim(valor) == ""){
			$("#errorNotNull"+i).html("No puede ser nulo");
			$("#errorNotNull"+i).attr("style","display:inline;");
			return false;
		}
	}
	return true;	
}

function checkInstitucion(){
	$("#errorInstitucion").attr("style","display:none;");
	var valor = $(".institucion").val();
	if(valor == "-1"){
		$("#errorInstitucion").html("Debe seleccionar una institución");
		$("#errorInstitucion").attr("style","display:inline;");
		return false;
	}

	return true;	
}

function checkProducciones(){
	for(i=0;i<mesesResolucion;i++){
		$("#errorProduccion"+i).attr("style","display:none;");
		var valor = $("#produccion"+i).val();
		if(valor != ""){
			valor = replaceAll(valor,'.','');
			if(!is_int(valor)){
				$("#errorProduccion"+i).html("Debe ser entero mayor a 0");
				$("#errorProduccion"+i).attr("style","display:inline;");
				return false;
			}
			if(valor < 0){
				$("#errorProduccion"+i).html("Debe ser entero mayor a 0");
				$("#errorProduccion"+i).attr("style","display:inline;");
				return false;
			}
		}
		/*else{
			$("#errorProduccion"+i).html("Debe ingresar un valor");
			$("#errorProduccion"+i).attr("style","display:inline;");
			return false;	
		}*/
	}
	return true;	
}

var produccionesReales = 0;
function checkProduccionesReales(){
	var ok = true;
	$(".errorProduccionReal").each(function(index){
		$(this).attr("style","display:none;");
		if($(this).val() == ""){
			$(this).html("Debe ingresar un valor");
			$(this).attr("style","display:inline;");
			ok = false;
		}
	});
	return ok;	
}

function checkFechas(max){
	var ok = true;
	for(i=0;i<max;i++){
		$("#errorFecha"+i).attr("style","display:none;");
		var valor = $("#fs").find("[nId='"+i+"']").val();
		if(valor != ""){
			if(!is_date(valor)){
				$("#errorFecha"+i).html("Formato de fecha Incorrecto");
				$("#errorFecha"+i).attr("style","display:inline;");
				ok = false;	
			}
		}
		else{
			$("#errorFecha"+i).html("Debe ingresar un valor");
			$("#errorFecha"+i).attr("style","display:inline;");
			ok = false;
		}
	}
	return ok;	
}

function formatThousands(value, separator) {
    var buf = [];
    value = String(value).split('').reverse();
    for (var i = 0; i < value.length; i++) {
        if (i % 3 === 0 && i !== 0) {
            buf.push(separator);
        }   
        buf.push(value[i]);
    }
    return buf.reverse().join('');
}

$(document).on('change','.dinero',function(e){
	var text = $(this).val();
	text = replaceAll(text,',','.');
	text = replaceAll(text,'.','');
	if(!isNaN(text)){
		var num = new Number(text);
		num = num.toFixed(0);
   		$(this).val(num);
   		$(this).val(formatThousands($(this).val(),'.'));
	}else{
		$(this).val(0);
	}
	
});

$(document).on('change','.dinero3',function(e){
	var text = $(this).val();
	text = replaceAll(text,'.','');
	var arr = text.split(",");
	if(arr.length > 2){
		$(this).val(0);
		return true;
	}
	else{
		var numero = arr[0];
		var decimal = 0;
		if(arr.length==2) decimal = arr[1];
		decimal = llenaConCeros(decimal);
		if(!isNaN(numero)){
			var num = new Number(numero);
			num = num.toFixed(0);
			num = formatThousands(num,'.');
			num = num + "," + decimal;
	   		$(this).val(num);
		}else{
			$(this).val(0);
		}
	}
});

function llenaConCeros(text){
	if(text.length > 4) 
		return text.substring(0,4);
	for(i=text.length;i<4;i++){
		text = text + "0";
	}
	return text;
}

$(document).on('change','.produccion',function(e){
	calculaSumaProd();	
});

$(document).on('change','.costo',function(e){
	calculaSumaCosto();	
});

function calculaSumaCosto(){
	var i = 0;
	var suma = 0;
	$(".costo").each(function(index){
		var numero = $("#costo"+i).val();
		numero = replaceAll( numero, ".", "" );
		suma += Number(numero);
		$("#costoAcum"+i).html("$ "+formatThousands(suma,'.'));
		i++;
	});
}

function calculaSumaProd(){
	var i = 0;
	var suma = 0;
	$(".produccion").each(function(index){
		var numero = $("#produccion"+i).val();
		numero = replaceAll( numero, ".", "" );
		suma += Number(numero);
		$("#prodAcum"+i).html("$ "+formatThousands(suma,'.'));
		i++;
	});
}

function checkFechaInicio(){
	var ok = true;
	
	$('.fecha_inicial').each(function(){
		var i = $(this).attr('nId');
		$("#errorFecha"+i).attr("style","display:none;");
		var fIni = getFecha($(this).val());
		var fMin = getFecha($('.fecha_contrato').html());
		if(fIni < fMin){
			ok = false;
			$("#errorFecha"+i).html("Fecha Inicio debe ser mayor a "+$('.fecha_contrato').html());
			$("#errorFecha"+i).attr("style","display:inline;");
		}
	});
	
	return ok;	
}

function daysInMonth(month,year) {
    return new Date(year, month, 0).getDate();
}

function backFecha(fecha){
	var month = new Number(fecha.getUTCMonth());
	month++;
	if(month < 10){
		month = "0"+month;
	}
	var day = new Number(fecha.getUTCDate());
	if(day < 10){
		day = "0"+day;
	}
	return day+"/"+month+"/"+fecha.getUTCFullYear();
}

function finProximoMes(fecha){
	var mes = fecha.getMonth();
	var agno = fecha.getFullYear();
	if(mes == 11){
		mes = 0;
		agno = agno + 1;
	}else{
		mes = mes + 1;
	}
	mes = mes + 1;
	return new Date(agno+"-"+mes+"-"+daysInMonth(mes,agno));
}


function inicio2MesesMas(fecha){
	var mes = fecha.getUTCMonth();
	var agno = fecha.getUTCFullYear();
	if(mes == 10){
		mes = 0;
		agno = agno + 1;
	}
	else if(mes == 11){
		mes = 1;
		agno = agno + 1;
	}else{
		mes = mes + 2;
	}
	mes = mes + 1;
	return new Date(agno+"-"+mes+"-01");
}

function checkCompareFechas(){
	$("#errorFecha1").attr("style","display:none;");
	var fFin = getFecha($('.fecha_final').val());
	var fIni = getFecha($('.fecha_inicio').val());
	if(fFin != null && fIni != null){
		var diasDiff = fFin.getTime() - fIni.getTime();
		if(diasDiff <= -1){
			$("#errorFecha1").html("Fecha Fin debe ser mayor a "+$('.fecha_inicio').val());
			$("#errorFecha1").attr("style","display:inline;");
			return false;
		}
	}
	else{
		return false;
	}
	return true;
}

function getFecha(fecha){
	if(fecha != null){
		var fixed = fecha.split("/");
		if(fixed.length == 3){
			if(!isNaN(fixed[2]) && !isNaN(fixed[1]) && !isNaN(fixed[0])){
				return new Date(fixed[2]+"-"+fixed[1]+"-"+fixed[0]);	
			}
			else{
				return null;
			}
		}else{
			return null;
		}
	}
}


function is_decimal(value){ 
	if(isNaN(value)){
		return false;
	}
	var s = value+"";
	if(s.length > 13){
		return false;
	}
	if(!isNaN(value)){
      	return true;
    } else { 
      	return false;
  	} 
}

function is_date(value){ 
	if(value!=null){
		var arr_date = value.split('/');
		if(arr_date.length == 3){
			if(!is_int(arr_date[0]) || !is_int(arr_date[1]) || !is_int(arr_date[2])){
				return false;
			}
			if(arr_date[0]>31 || arr_date[0]<1 || arr_date[1]>12 || arr_date[1]<1 || arr_date[2]<1000){
				return false;
			}		
		}else{
			return false;
		}
		return true;
	}
}

function is_int(value){ 
	if(isNaN(value)){
		return false;
	}
	var s = value+"";
	if(s.length > 11){
		return false;
	}
	if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
      	return true;
    } else { 
      	return false;
  	} 
}

function hideEmptyHeaders(){
	$('.templateTarget').filter(function(){return $.trim($(this).text())==='';}).siblings('.templateHead').hide();
}

