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
			console.log("evento");
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


	$('.suma').each(function(e){
		console.log($(this));
	});

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