<?php

Yii::app()->getController()->pageTitle="Gastos de " . $gastoNombre;
?>

<h3>Registros de gastos de <?=$gastoNombre?></h3>

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/redmond/jquery-ui.css"/>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>


<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route,['policy'=>$model->policy]),
	'method'=>'get',
)); 


?>

<table>
 <tr>
  <td>
  	<?php echo $form->label($model,'igual'); ?>
  </td>
  <td>
  	<?php echo $form->dropDownList($model,'igual', CHtml::listData(array(array('id'=>'TODOS','nombre'=>'TODOS'),array('id'=>'SIN ERRORES','nombre'=>'SIN ERRORES'),array('id'=>'CON ERRORES','nombre'=>'CON ERRORES')), 'id', 'nombre')); ?>
  </td>
  <td>
  	<?php echo $form->label($model,'fecha_inicio'); ?>
  </td>
  <td>
  	<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker',
			array(
				'model'=>$model,
				'language' => 'es',
				'attribute'=>'fecha_inicio',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'yy-mm-dd',
					'changeYear'=>true,
					'changeMonth'=>true,
				),
				'htmlOptions'=>array(
					'style'=>'width:70px;',
				),
			)
		);
	?>
	</td>
	<td>
  	<?php echo $form->label($model,'fecha_fin'); ?>
  </td>
  <td>
  	<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker',
			array(
				'model'=>$model,
				'language' => 'es',
				'attribute'=>'fecha_fin',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'dd/mm/yy',
					'changeYear'=>true,
					'changeMonth'=>true,
				),
				'htmlOptions'=>array(
					'style'=>'width:70px;',
				),
			)
		);
	?>
	</td>
	<td>
		<?php echo CHtml::submitButton('Filtrar'); ?>
	</td>
 </tr>
</table>
<?php $this->endWidget(); 


?>



<div class="wrapper">
	<img class="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gear.gif"/>
	<table id="datos" class='display nowrap' data-order='[[ 1, "asc" ]]' style="width:100%;height:100%;display:none">
		<thead>
			<tr>
			<?php
			foreach ($cabeceras as $th) {
				if (gettype($th) == 'array') {
					$atributos = "";
					$atributos_input = "";
					$ancho = 50;
					if (isset($th['width'])) {
						$ancho = Tools::CELL_SIZES[$th['width']];
						$atributos .= "style='width:" . $ancho . "px' ";
					}
					if (isset($th['format'])) {
						if ($th['format'] == 'date') {
							$atributos_input .= "class='datepicker' ";
						}
					}
					if (isset($th['name'])) {
						echo "<th " . $atributos . " title='" . $th['name'] . "'><input style='width:" . $ancho . "px' $atributos_input type='text' placeholder='" . $th['name'] . "' /></th>";
					}
				}
			}
			?>
			
			</tr>
		</thead>
		<tbody>
		<?php 
			$totales = [];
			foreach($datos as $fila):?>
			<tr>
				<?php foreach($extra_datos as $i => $extra_dato): 
					$campo = $extra_dato['campo'];
					$estilos = "";
					$valor = $fila->$campo;
					$class = "";
					if(isset($extra_dato['dots'])){
						$tamaño = $extra_dato['dots'];
						$class .= "dots-$tamaño ";
					}
					if(isset($extra_dato['format'])){
						if($extra_dato['format'] == "money"){
							$estilos .= "text-align:right;";
							$valor = "$".number_format((int)$fila->$campo,"0","",".");
						}
						if($extra_dato['format'] == "imagen"){
							$valor = '<a target="_blank" href="' . $fila->$campo . '"><img src="' . Yii::app()->request->baseUrl . '/images/search.png"></a>';
						}
						if($extra_dato['format'] == "enlace"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							$valor = '<a href="' . CController::createUrl($extra_dato['url']) . '?' . $params .'">' . $valor . '</a>';
						}
					}
					if(isset($extra_dato['acumulado'])){
						$acumulado = $extra_dato['acumulado'];
						$class .= " $acumulado";
					}
					?>
					<td campo="<?=$campo?>" style='<?=$estilos?>' data-toggle data-placement class="<?=$class?>"><?=$valor?></td>
				<?php endforeach;?>
			</tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
			<tr>
				<th>Totales:</th>
				<?php for($i = 1; $i < count($extra_datos); $i++):?>
					<th class="footer_<?=$extra_datos[$i]["campo"]?>"></th>
				<?php endfor;?>
			</tr>
		</tfoot>
		
	</table>
</div>

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
    max-width: <?=Tools::CELL_SIZES['lg']?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.dots-md{
    max-width: <?=Tools::CELL_SIZES['md']?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.dots-sm{
    max-width: <?=Tools::CELL_SIZES['sm']?>px !important;
    overflow:hidden; 
    white-space:nowrap; 
    text-overflow: ellipsis;
}
.dots-xs{
    max-width: <?=Tools::CELL_SIZES['xs']?>px !important;
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