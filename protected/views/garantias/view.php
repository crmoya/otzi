<?php
/* @var $this GarantiasController */
/* @var $model Garantias */

$this->breadcrumbs=array(
	'Garantias'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Listar Garantias', 'url'=>array('index')),
	array('label'=>'Administrar Garantias', 'url'=>array('admin')),
);
?>

<h1>View Garantias #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'numero',
		'monto',
		'fecha_vencimiento',
		'instituciones_id',
		'tipos_garantias_id',
		'contratos_id',
		'objetos_garantias_id',
		'modificador_id',
		'creador_id',
		'observacion',
		'tipo_monto',
		'estado_garantia',
		'fecha_devolucion',
	),
)); ?>
