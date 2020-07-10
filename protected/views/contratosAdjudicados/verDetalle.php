<?php
/* @var $this ContratosAdjudicadosController */
/* @var $model ContratosAdjudicados */


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('contratos-adjudicados-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Detalle de Flujos Reales</h1>

<div class="vinculos">
<?php echo CHtml::link('Volver', Yii::app()->createUrl("contratosAdjudicados/admin",array()))?>
<?php echo CHtml::link('Exportar a Excel', Yii::app()->createUrl("contratosAdjudicados/exportarFlujos",array('id'=>$id)))?>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'contratos-adjudicados-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array('name'=>'mes','value'=>'Tools::getMes($data->mes)'),
		'agno',
		array(
        	'name'=>'produccion',
			'value'=>'Yii::app()->format->number($data->produccion)'
		),
	),
));

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_fecha_inicio').datepicker($.datepicker.regional[ 'es' ]);
	$('#datepicker_for_fecha_termino').datepicker($.datepicker.regional[ 'es' ]);
}
");

 ?>
