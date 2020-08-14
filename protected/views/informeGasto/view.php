<?php
/* @var $this InformeGastoController */
/* @var $model InformeGasto */

$this->breadcrumbs=array(
	'Volver a Gastos'=>array('//gastoCompleta/admin?policy='.$model->politica_id),
	$model->id,
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'titulo',
		'numero',
		['name'=>'fecha_envio','value'=>Tools::backFecha($model->fecha_envio)],
		['name'=>'fecha_cierre','value'=>Tools::backFecha($model->fecha_cierre)],
		'nombre_empleado',
		'rut_empleado',
		'aprobado_por',
		'politica',
		['name'=>'estado','value'=>Tools::fixEstadoInforme($model->estado)],
		['name' => 'total', 'value' => "$".number_format($model->total,0,",",".")],
		['name' => 'total_aprobado', 'value' => "$".number_format($model->total_aprobado,0,",",".")],
		'nro_gastos',
		'nro_gastos_aprobados',
		'nro_gastos_rechazados',
	),
)); ?>
