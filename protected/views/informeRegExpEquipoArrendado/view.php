<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Arrendados'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List InformeRegExpEquipoArrendado', 'url'=>array('index')),
	array('label'=>'Create InformeRegExpEquipoArrendado', 'url'=>array('create')),
	array('label'=>'Update InformeRegExpEquipoArrendado', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete InformeRegExpEquipoArrendado', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage InformeRegExpEquipoArrendado', 'url'=>array('admin')),
);
?>

<h1>View InformeRegExpEquipoArrendado #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'fecha',
		'reporte',
		'observaciones',
		'equipo',
		'horasReales',
		'combustible',
		'repuesto',
		'horasPanne',
		'id_reg',
	),
)); ?>
