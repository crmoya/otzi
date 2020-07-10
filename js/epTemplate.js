$(document).ready(function(e){
	
	var i = 0;
	var ie = 0;
	var anticipos = Array();
	var canjes = Array();
	var eps = Array();
	
	//solo se puede agregar un anticipo y canje por mes
	
	$(".eps").each(function(e){
		$(this).children().each(function(e){
			if($(this).hasClass('addEP') || $(this).hasClass('agregados') || $(this).hasClass('tipoEP')){
				$(this).attr('i',i);
				anticipos[i] = false;
				canjes[i] = false;
				eps[i]=0;
			}
		});
		i++;
	});
	
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
			
			$(".agregados").each(function(e){
				if($(this).attr("i") == i){
					var html = 	'<div class="rodeado ep" j="'+i+'_'+eps[i]+'">'+
									'<span><strong>EP de Anticipo</strong></span>'+
									'<span>&nbsp;&nbsp;&nbsp;</span>'+
									'<span>Valor: <input type="text" name="EpAnticipo['+i+'][valor]" value="" class="dinero"/></span>'+
									'<span>&nbsp;</span>'+
									'<span>Comentarios: <input type="text" name="EpAnticipo['+i+'][comentarios]" value="" size="50"/></span>'+
									'<input type="hidden" name="EpAnticipo['+i+'][mes]" value="'+mes+'"/>'+
									'<input type="hidden" name="EpAnticipo['+i+'][agno]" value="'+agno+'"/>'+
									'<input type="hidden" name="EpAnticipo['+i+'][resoluciones_id]" value="'+resoluciones_id+'"/>'+
									'<span>&nbsp;</span>'+
									'<span tipo="anticipo" class="btn delEP" i="'+i+'" j="'+i+'_'+eps[i]+'">Eliminar</span>'+
								'</div>';
					if(!anticipos[i]){
						$(this).append(html);
						anticipos[i] = true;
						eps[i]=eps[i]+1;
					}
				}
			});
		}
		if(tipo == 'canje'){
			$(".agregados").each(function(e){
				if($(this).attr("i") == i){
					var html = 	'<div class="rodeado ep" j="'+i+'_'+eps[i]+'">'+
									'<span><strong>EP Canje Retenciones</strong></span>'+
									'<span>&nbsp;&nbsp;&nbsp;</span>'+
									'<span>Valor: <input type="text" name="EpCanjeRetencion['+i+'][valor]" value="" class="dinero"/></span>'+
									'<span>&nbsp;</span>'+
									'<span>Comentarios: <input type="text" name="EpCanjeRetencion['+i+'][comentarios]" value="" size="40"/></span>'+
									'<input type="hidden" name="EpCanjeRetencion['+i+'][mes]" value="'+mes+'"/>'+
									'<input type="hidden" name="EpCanjeRetencion['+i+'][agno]" value="'+agno+'"/>'+
									'<input type="hidden" name="EpCanjeRetencion['+i+'][resoluciones_id]" value="'+resoluciones_id+'"/>'+
									'<span>&nbsp;</span>'+
									'<span tipo="canje" class="btn delEP" i="'+i+'" j="'+i+'_'+eps[i]+'">Eliminar</span>'+
								'</div>';
					if(!canjes[i]){
						$(this).append(html);
						canjes[i] = true;
						eps[i]=eps[i]+1;
					}
				}
			});
		}
		if(tipo == 'obra'){
			$(".agregados").each(function(e){
				if($(this).attr("i") == i){
					var html = 	'<div class="rodeado ep" j="'+i+'_'+eps[i]+'">'+
									'<span><strong>EP Obra</strong></span>'+
									'<span>&nbsp;&nbsp;&nbsp;</span>'+
									'<span>Producción: &nbsp;&nbsp;<input type="text" name="EpObra['+ie+'][produccion]" value="" class="dinero"/></span>'+
									'<span>&nbsp;</span>'+
									'<span>Costo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="EpObra['+ie+'][costo]" value="" class="dinero"/></span>'+
									'<span>&nbsp;</span>'+
									'<span>Reajuste: <input type="text" name="EpObra['+ie+'][reajuste]" value="" class="dinero"/></span>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>Retención: &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="EpObra['+ie+'][retencion]" value="" class="dinero"/></span>'+
									'<span>&nbsp;</span>'+
									'<span>Descuento: <input type="text" name="EpObra['+ie+'][descuento]" value="" class="dinero"/></span>'+
									'<br/>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+'<span>&nbsp;</span>'+
									'<span>Comentarios: <input type="text" name="EpObra['+ie+'][comentarios]" value="" size="50"/></span>'+
									'<input type="hidden" name="EpObra['+ie+'][mes]" value="'+mes+'"/>'+
									'<input type="hidden" name="EpObra['+ie+'][agno]" value="'+agno+'"/>'+
									'<input type="hidden" name="EpObra['+ie+'][resoluciones_id]" value="'+resoluciones_id+'"/>'+
									'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
									'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
									'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
									'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
									'<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
									'<span tipo="canje" class="btn delEP" i="'+i+'" j="'+i+'_'+eps[i]+'">Eliminar</span>'+
								'</div>';
					$(this).append(html);
					eps[i]=eps[i]+1;
					ie++;
					
					$("#eps_obra").val(ie);
				}
			});
		}
	});
	
});