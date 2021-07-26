<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

$this->breadcrumbs=array(
	'Gasto Completas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GastoCompleta', 'url'=>array('index')),
	array('label'=>'Create GastoCompleta', 'url'=>array('create')),
	array('label'=>'Update GastoCompleta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GastoCompleta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GastoCompleta', 'url'=>array('admin')),
);
?>

<h1>View GastoCompleta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'retenido',
		'cantidad',
		'centro_costo_faena',
		'departamento',
		'faena',
		'impuesto_especifico',
		'iva',
		'km_carguio',
		'litros_combustible',
		'monto_neto',
		'nombre_quien_rinde',
		'nro_documento',
		'periodo_planilla',
		'rut_proveedor',
		'supervisor_combustible',
		'tipo_documento',
		'unidad',
		'vehiculo_equipo',
		'vehiculo_oficina_central',
		'gasto_id',
	),
)); ?>
