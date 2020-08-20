<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

?>
<h3>Registros de gastos de <?=$gastoNombre?></h3>


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
 </tr>
 <tr>
  <td colspan="2">
    <div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar'); ?>
	</div>
  </td>
 </tr>
</table>
<?php $this->endWidget(); ?>


<?php echo CHtml::link('Exportar a Excel','exportar?policy='.$model->policy); ?>

<div class="wrapper">

<?php 

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('gasto-completa-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_fecha').datepicker({
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    });
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gasto-completa-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'columns'=>array(
		[
			'name'=>'proveedor', 
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->supplier),"",["title"=>"$data->supplier", "data-toggle"=>"tooltip","style"=>"text-decoration: none;color:#555;"])', 
			'htmlOptions'=>['style'=>'max-width:120px !important;'],
		],
		[
			'name' => 'fecha',
            'value' => array($model, 'gridDataColumn'),
            'filter' => $this->widget(
                'zii.widgets.jui.CJuiDatePicker',
                array(
                    'model' => $model,
                    'attribute' => 'fecha',
                    'language' => 'es',
                    'htmlOptions' => array(
                        'id' => 'datepicker_for_fecha',
                        'size' => '10',
                    ),
                    'defaultOptions' => array( 
                        'showOn' => 'focus',
                        'dateFormat' => 'dd/mm/yy',
                        'showOtherMonths' => true,
                        'selectOtherMonths' => true,
                        'changeMonth' => true,
                        'changeYear' => true,
                        'showButtonPanel' => true,
                    )
                ),
				true
			)
		],
		['name' => 'impuesto_especifico', 'value' => '"$".number_format((int)$data->impuesto_especifico,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'iva', 'value' => '"$".number_format((int)$data->iva,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'monto_neto', 'value' => '"$".number_format((int)$data->monto_neto,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'total', 'value' => '"$".number_format($data->tot,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		[
			'name'=>'categoria', 
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->category),"",["title"=>"$data->category", "data-toggle"=>"tooltip","style"=>"text-decoration: none;color:#555;"])', 
			'htmlOptions'=>['style'=>'max-width:100px !important;'],
		],
		['name'=>'grupocategoria', 'value'=>'$data->categorygroup'],
		[
			'name'=>'nota', 
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->note),"",["title"=>"$data->note", "data-toggle"=>"tooltip","style"=>"text-decoration: none;color:#555;"])', 
			'header'=>'Nota',
			'htmlOptions'=>['style'=>'max-width:60px !important;'],
		],
		//'retenido',
		'cantidad',
		'unidad',
		[
			'name'=>'centro_costo_faena', 
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->centro_costo_faena),"",["title"=>"$data->centro_costo_faena", "data-toggle"=>"tooltip","style"=>"text-decoration: none;color:#555;"])', 
			'htmlOptions'=>['style'=>'max-width:100px !important;'],
		],
		//'departamento',
		//'faena',
		//'impuesto_especifico',
		//'iva',
		//'km_carguio',
		//'litros_combustible',
		//'monto_neto',
		'nombre_quien_rinde',
		[
			'name'=>'nro_documento', 
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->nro_documento),"",["title"=>"$data->nro_documento", "data-toggle"=>"tooltip","style"=>"text-decoration: none;color:#555;"])', 
			'htmlOptions'=>['style'=>'max-width:100px !important;'],
		],
		//'periodo_planilla',
		'rut_proveedor',
		//'supervisor_combustible',
		[
			'name'=>'tipo_documento', 
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->tipo_documento),"",["title"=>"$data->tipo_documento", "data-toggle"=>"tooltip","style"=>"text-decoration: none;color:#555;"])', 
			'htmlOptions'=>['style'=>'max-width:100px !important;'],
		],
		[
			'name'=>'vehiculo_equipo', 
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->vehiculo_equipo),"",["title"=>"$data->vehiculo_equipo", "data-toggle"=>"tooltip","style"=>"text-decoration: none;color:#555;"])', 
			'htmlOptions'=>['style'=>'max-width:80px !important;'],
		],
		[
			'name' => 'folio',
            'type' => 'raw',
            'value'=>'CHtml::link($data->numeroinforme, array("informeGasto/view", "id"=>$data->folioinforme))',
		],
		//'vehiculo_oficina_central',
		[
			'header' => 'Imagen',
			'type' => 'raw',
			'value'=>'($data->image!="SIN IMAGEN")?
				CHtml::link("<img src=\''.Yii::app()->request->baseUrl.'/images/search.png\'>", ($data->image),["target"=>"_blank"])
        		:"SIN IMAGEN"',
		],
	),
)); ?>
</div>
<style>
.wrapper{
	width: 100%;
	overflow: auto;	
	margin-left:-60px;
	padding-right:100px;
}
.span-19{
	width: 100%;
}
tr td{
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
}
</style>
<script>
	
</script>