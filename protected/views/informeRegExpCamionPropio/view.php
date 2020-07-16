<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Propios'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List InformeRegExpEquipoPropio', 'url'=>array('index')),
	array('label'=>'Create InformeRegExpEquipoPropio', 'url'=>array('create')),
	array('label'=>'Update InformeRegExpEquipoPropio', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete InformeRegExpEquipoPropio', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage InformeRegExpEquipoPropio', 'url'=>array('admin')),
);
?>

<h1>View InformeRegExpEquipoPropio #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'fecha',
		'reporte',
		'observaciones',
		'equipo',
		'codigo',
		'horasReales',
		'combustible',
		'repuesto',
		'horasPanne',
	),
)); ?>
