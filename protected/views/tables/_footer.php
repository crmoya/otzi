<script>
$(document).ready( function () {

	// Setup - add a text input to each header cell
	$('#datos thead th').each(function() {
		var title = $('#datos thead th').eq($(this).index()).text();
		$(this).html('<input type="text" placeholder="' + title + '" >');
	});

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
							data = $('<p>' + data + '</p>').text();
							return $.isNumeric(data.replace('.', '')) ? data.replace('.', '') : data;
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
			console.log("evento");
			e.stopPropagation();
		});
	});

	$('.dataTables_length').hide();
	$('.dataTables_filter').hide();
	$('.buttons-excel').html('<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/xls.png"/>');
});
</script>
<style>
.dt-button{
	background: transparent !important;
    border: none !important;
}
.dots{
    max-width: 100px !important;
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