<script>
$(document).ready( function () {

	// DataTable
	var table = $('#datos').DataTable({
		dom: 'Bfrtip',
		buttons: [
			{
				extend: 'excelHtml5',
				text: 'Excel',
				footer: true,
				enabled: true,
				action: function(e, dt, button, config) {
					var before = $('#datos thead').html();
					$('#datos thead th input').each( function () {
						var title = $(this).attr('placeholder');
						$(this).parent().html( title );
					} );
					$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
					$('#datos thead').html(before);
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
							data = data.replace(",",".");
							data = $.isNumeric(data.replace('.', '')) ? data.replace('.', '') : data;
							data = $.isNumeric(data.replace(',', '')) ? data.replace(',', '') : data;
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
			"thousands": "."
		},
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
  
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$.]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

			var totales = Array();
			var totalesParciales = Array();

			<?php
			for($j=0; $j<count($extra_datos); $j++){
				$extra_dato = $extra_datos[$j];
				echo "totales[" . $j . "] = 0;";
				echo "totalesParciales[" . $j . "] = 0;";
				if(isset($extra_dato['acumulado'])){
					$operacion = $extra_dato['acumulado'];
					$moneda = "";
					if(isset($extra_dato['format'])){
						if($extra_dato['format'] == "money"){
							$moneda = "'$'+";
						}
					}

					if($operacion == "suma"){

						if(substr($extra_dato['format'], 0, 7 ) === "decimal"){
							echo "// Total over all pages 
							totales[" . $j . "] = api 
								.column( " . $j . " , { search: 'applied' } ) 
								.data() 
								.reduce( function (a, b) { 
								   return parseFloat(a) + parseFloat(b); 
								}, 0 );";

							echo "// Total over this page
									totalesParciales[" . $j . "] = api
										.column(  " . $j . " , { page: 'current'} )
										.data()
										.reduce( function (a, b) {
											return parseFloat(a) + parseFloat(b); 
										}, 0 );

									// Update footer
									var total = " . $moneda. "new Intl.NumberFormat('es-CL', {
										minimumFractionDigits: 0,
										maximumFractionDigits: 3
									}).format(totales[" . $j . "]);
									var totalParcial = " . $moneda. "new Intl.NumberFormat('es-CL', {
										minimumFractionDigits: 0,
										maximumFractionDigits: 3
									}).format(totalesParciales[" . $j . "]);
									var html = totalParcial + '<br/>' + total;
									$( api.column( " . $j . " ).footer() ).html(html);";
						}
						else{
							echo "// Total over all pages 
							totales[" . $j . "] = api 
								.column( " . $j . " , { search: 'applied' } ) 
								.data() 
								.reduce( function (a, b) { 
								   return intVal(a) + intVal(b); 
								}, 0 );";

							echo "// Total over this page
									totalesParciales[" . $j . "] = api
										.column(  " . $j . " , { page: 'current'} )
										.data()
										.reduce( function (a, b) {
											return intVal(a) + intVal(b); 
										}, 0 );

									// Update footer
									var total = " . $moneda. "new Intl.NumberFormat('es-CL', {
										minimumFractionDigits: 0,
										maximumFractionDigits: 3
									}).format(totales[" . $j . "]);
									var totalParcial = " . $moneda. "new Intl.NumberFormat('es-CL', {
										minimumFractionDigits: 0,
										maximumFractionDigits: 3
									}).format(totalesParciales[" . $j . "]);
									var html = totalParcial + '<br/>' + total;
									$( api.column( " . $j . " ).footer() ).html(html);";
						}
						
					}
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

});
</script>
<style>
.dt-button{
	background: transparent !important;
    border: none !important;
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
</style>