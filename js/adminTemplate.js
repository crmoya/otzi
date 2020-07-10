$(document).ready(function(e){
	
	var arr_anticipos = str_anticipo.split('_');
	var arr_retenciones = str_retencion.split('_');
	
	var anticipos = Array();
	var canjes = Array();
	for(i=0;i<total;i++){
		anticipos[i] = false;
		canjes[i] = false;
	}
	
	var i = $("#i").val();
	for(k=0;k<i;k++){
		if(contains(k,arr_anticipos)){
			anticipos[k]=true;
		}
		if(contains(k,arr_retenciones)){
			canjes[k]=true;
		}
	}	
	
	var total = $("#total").val();	
	
	function contains(val,arr){
		val = val+"";
		for(j=0;j<arr.length;j++){
			if((arr[j]+"") === val){
				return true;
			}
		}
		return false;
	}
	
        $(document).on('click','.delEP',function(e){
		
		var j = $(this).attr("j");
		var i = $(this).attr("i");
		var tipo = $(this).attr("tipo");
		if(tipo == "anticipo"){
			anticipos[i] = false;
		}
		if(tipo == "canje"){
			canjes[i] = false;
		}
		$('.ep').each(function(e){
			var jEp = $(this).attr("j");
			if(jEp == j){
				$(this).remove();
			}
		});
	});
	
	
	$(".addEP").click(function(e){	
		var i = $(this).attr("i");
		var tipo = "";
		var mes = $(this).attr("mes");
		var agno = $(this).attr("agno");
		var resoluciones_id = $(this).attr("resoluciones_id");
		
		$(".tipoEP").each(function(e){
			if($(this).attr('i')==i){
				tipo = $(this).find(":selected").val();
			}
		});
		if(tipo == 'anticipo'){
			var html = 	'<div class="rodeado ep" i="'+i+'" j="'+total+'">'+
							'<span><strong>EP de Anticipo</strong></span>'+
							'<span>&nbsp;&nbsp;&nbsp;</span>'+
							'<span>Valor: <input type="text" name="EpAnticipo['+total+'][valor]" value="" class="dinero"/></span>'+
							'<span>&nbsp;</span>'+
							'<span>Comentarios: <input type="text" name="EpAnticipo['+total+'][comentarios]" value="" size="50"/></span>'+
							'<input type="hidden" name="EpAnticipo['+total+'][mes]" value="'+mes+'"/>'+
							'<input type="hidden" name="EpAnticipo['+total+'][agno]" value="'+agno+'"/>'+
							'<input type="hidden" name="EpAnticipo['+total+'][resoluciones_id]" value="'+resoluciones_id+'"/>'+
							'<span>&nbsp;</span>'+
							'<span tipo="anticipo" class="btn delEP" i="'+i+'" j="'+total+'">Eliminar</span>'+
						'</div>';
			if(!anticipos[i]){
				$("#agregados_"+i).append(html);
				anticipos[i] = true;
				total++;
			}
		}
		if(tipo == 'canje'){
			var html = 	'<div class="rodeado ep" i="'+i+'" j="'+total+'">'+
							'<span><strong>EP Canje Retenciones</strong></span>'+
							'<span>&nbsp;&nbsp;&nbsp;</span>'+
							'<span>Valor: <input type="text" name="EpCanjeRetencion['+total+'][valor]" value="" class="dinero"/></span>'+
							'<span>&nbsp;</span>'+
							'<span>Comentarios: <input type="text" name="EpCanjeRetencion['+total+'][comentarios]" value="" size="40"/></span>'+
							'<input type="hidden" name="EpCanjeRetencion['+total+'][mes]" value="'+mes+'"/>'+
							'<input type="hidden" name="EpCanjeRetencion['+total+'][agno]" value="'+agno+'"/>'+
							'<input type="hidden" name="EpCanjeRetencion['+total+'][resoluciones_id]" value="'+resoluciones_id+'"/>'+
							'<span>&nbsp;</span>'+
							'<span tipo="canje" class="btn delEP" i="'+i+'" j="'+total+'">Eliminar</span>'+
						'</div>';
			if(!canjes[i]){
				$("#agregados_"+i).append(html);
				canjes[i] = true;
				total++;
			}
		}
		if(tipo == 'obra'){
			var html = 	'<div class="rodeado ep" j="'+total+'" i="'+i+'">'+
							'<span><strong>EP Obra</strong></span>'+
							'<span>&nbsp;&nbsp;&nbsp;</span>'+
							'<span>Producción: &nbsp;&nbsp;<input type="text" name="EpObra['+total+'][produccion]" value="" class="dinero"/></span>'+
							'<span>&nbsp;</span>'+
							'<span>Costo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="EpObra['+total+'][costo]" value="" class="dinero"/></span>'+
							'<span>&nbsp;</span>'+
							'<span>Reajuste: <input type="text" name="EpObra['+total+'][reajuste]" value="" class="dinero"/></span>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>Retención: &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="EpObra['+total+'][retencion]" value="" class="dinero"/></span>'+
							'<span>&nbsp;</span>'+
							'<span>Descuento: <input type="text" name="EpObra['+total+'][descuento]" value="" class="dinero"/></span>'+
							'<br/>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
							'<span>Comentarios: <input type="text" name="EpObra['+total+'][comentarios]" value="" size="50"/></span>'+
							'<input type="hidden" name="EpObra['+total+'][mes]" value="'+mes+'"/>'+
							'<input type="hidden" name="EpObra['+total+'][agno]" value="'+agno+'"/>'+
							'<input type="hidden" name="EpObra['+total+'][resoluciones_id]" value="'+resoluciones_id+'"/>'+
							'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
							'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
							'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
							'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
							'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
							'<span tipo="canje" class="btn delEP" i="'+i+'" j="'+total+'">Eliminar</span>'+
						'</div>';
			$("#agregados_"+i).append(html);
			total++;
		}
	});
	
});