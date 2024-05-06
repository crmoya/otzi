<script>
$(document).ready( function () {


	Number.prototype.format = function (n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));
		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};

	function format(valor) {
		valor = parseFloat(valor);
		return valor.format(0, 3, '.', ',');
	}

	function decimalAdjust(type, value, exp) {
		// Si el exp no está definido o es cero...
		if (typeof exp === 'undefined' || +exp === 0) {
		return Math[type](value);
		}
		value = +value;
		exp = +exp;
		// Si el valor no es un número o el exp no es un entero...
		if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
		return NaN;
		}
		// Shift
		value = value.toString().split('e');
		value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
		// Shift back
		value = value.toString().split('e');
		return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
	}

	// Decimal round
	if (!Math.round10) {
		Math.round10 = function(value, exp) {
		return decimalAdjust('round', value, exp);
		};
	}
	
	

	// DataTable
	var table = $('#datos').DataTable({
		dom: 'Blfrtip',
    	lengthMenu: [
			[10, 25, 50, 100, -1],
			['10 registros', '25 registros', '50 registros', '100 registros', 'Mostrar todo']
		],
		columns: [
			<?php
			for($j=0; $j<count($extra_datos); $j++){
				if(isset($extra_datos[$j]['ordenable'])){
					if($extra_datos[$j]['ordenable'] == "false"){
						echo "{ orderable: false }, ";
					}
					else{
						echo "null, ";
					}
				}
				else{
					echo "null, ";
				}
			}
			?>
		],
		buttons: [
			{ extend: 'pageLength', className: 'buttons-collection'},
			{
				extend: 'excelHtml5',
				text: 'Excel',
				footer: true,
				enabled: true,
				action: function(e, dt, button, config) {
					$('#datos thead th input').each( function () {
						var title = $(this).attr('placeholder');
						$(this).parent().html( title );
					});
					$('#datos tfoot th').each( function () {
						$(this).html("");
					});
					$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
					location.reload();
				},
				exportOptions: {
					columns: [
						<?php
						for($i = 0; $i < count($extra_datos); $i++){
							echo (in_array("exportable",$extra_datos[$i]))?$i.",":"";
						}
						?>
					],
					format: {
						body: function(data, row, column, node) {
							data = data.replace("$","");
							var numero = data.replaceAll(".","");
							numero = numero.replaceAll(",","");
							if($.isNumeric(numero)){
								data = data.replaceAll(".","");
								data = data.replaceAll(',', '.');
							}
							return data;
						}
					},
					extend: 'csv',
					footer: true,
					text: 'Excel',
				}
			}
		],
		initComplete: function (settings, data) {
			$('#datos').show();
			$('.loading').hide();
		},
		language: {
			//"url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
			"decimal": ",",
			"thousands": ".",
			buttons: {
				pageLength: {
					_: 'Mostrar %d registros <span class="dt-button-down-arrow">▼</span>',
					'-1': 'Mostrar Todo'
				}
			}
		},
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
  
			var totales = Array();
			var totalesParciales = Array();

			<?php
			$noVisibles = 0;
			for($j=0; $j<count($extra_datos); $j++){
				echo "totales[".$j."] = 0;";
				echo "totalesParciales[".$j."] = 0;";
				$extra_dato = $extra_datos[$j];

				if(isset($extra_dato['visible'])){
					if($extra_dato['visible']=="false"){
						$noVisibles++;
					}
				}

				if(isset($extra_dato['acumulado'])){
					$operacion = $extra_dato['acumulado'];
					$moneda = "";
					if(isset($extra_dato['format'])){
						if($extra_dato['format'] == "money"){
							$moneda = "'$'+";
						}
					}
					if($operacion == "suma"):
					?>
						var indice = <?=$j?>;
						var decimales = 0;
						if($('.decimales').val()){
							decimales = parseInt($('.decimales').val());
						}
						var api = this.api();
						api.column(indice, { search: 'applied' } )
							.data()
							.each(function(data){
								var numero = data.replaceAll('$','');
								numero = numero.replaceAll('.','');
								numero = numero.replaceAll(',','.');
								totalesParciales[indice] += parseFloat(numero);
							} );

						api.column(indice)
							.data()
							.each(function(data){
								var numero = data.replaceAll('$','');
								numero = numero.replaceAll('.','');
								numero = numero.replaceAll(',','.');
								totales[indice] += parseFloat(numero);
							} );


						var parteDecimalTotales = parseFloat(totales[indice]) - parseInt(totales[indice]);
						var decimalesTotales = Math.round10(parteDecimalTotales, -decimales);
						var parteDecimalTotalesParciales = parseFloat(totalesParciales[indice]) - parseInt(totalesParciales[indice]);
						var decimalesTotalesParciales = Math.round10(parteDecimalTotalesParciales, -decimales);

						totales[indice] = format((totales[indice] + "").replaceAll('.',','));
						if(parteDecimalTotales > 0){
							totales[indice] = totales[indice] + "," + (decimalesTotales + "").substr(2);
						}
						totalesParciales[indice] = format((totalesParciales[indice] + "").replaceAll('.',','));
						if(parteDecimalTotalesParciales > 0){
							totalesParciales[indice] = totalesParciales[indice] + "," + (decimalesTotalesParciales + "").substr(2);
						}
						
					<?php 
					endif; 
					echo 	"var html = " . $moneda. "totalesParciales[indice] + '<br/>' + " . $moneda. "totales[indice];" . 
							"$( api.column(indice-".$noVisibles.").footer() ).html(html);";
				}
				
			}
			
			?>
  
			
        }
	});

	// Apply the search
	table.columns().eq(0).each(function(colIdx) {
		$('input', table.column(colIdx).header()).on('keyup change', function() {
			table
				.column(colIdx)
				.search(this.value)
				.draw();
		});

		$('input', table.column(colIdx).header()).on('click', function(e) {
			e.stopPropagation();
		});
	});

	$('.dataTables_length').hide();
	$('.dataTables_filter').hide();
	$('.buttons-excel').html('<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/xls.png"/>');

	$('#datos td').attr("data-toggle",'tooltip');
	$('#datos td').attr("data-placement",'top');
	$('#datos td').mouseenter(function(e){
		$(this).attr('title',$(this).text());
	});
	$('[data-toggle="tooltip"]').tooltip();

	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '<Ant',
		nextText: 'Sig>',
		currentText: 'Hoy',
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$( ".datepicker" ).datepicker({ 
		"dateFormat": "yy-mm-dd"
	});
	$.fn.dataTable.moment( 'YYYY-MM-DD' );


	var allSelected = false;
	$('.select-all').click(function(e){
		var checked = 'checked';
		if(allSelected){
			checked = '';
		}
		allSelected = !allSelected;
		$('.check-adjunto').prop('checked',checked);
	});


	$('.validar-1').attr('title','validar');
	$('.validar-2').attr('title','realizar segunda validación');
	$('.full-validado').attr('title','');


	
});
</script>
<style>
.dt-button{
	background: transparent !important;
    border: none !important;
}
.dots-xl{
    max-width: <?=Tools::$XL_CELL?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.dots-lg{
    max-width: <?=Tools::$LG_CELL?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.dots-md{
    max-width: <?=Tools::$MD_CELL?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.dots-sm{
    max-width: <?=Tools::$SM_CELL?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.dots-xs{
    max-width: <?=Tools::$XS_CELL?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.wrapper{
	width:100%;
	overflow-x: auto;
}
.span-19{
	width:100%;
}
.loading{
	margin: 0 auto;
}
.validar-1:hover, .validar-2:hover{
	cursor: pointer;
}
.buttons-page-length {
    border: 1px solid rgb(89, 91, 94) !important;
    background: rgba(255, 255, 255, 0.15) !important;
}
</style>