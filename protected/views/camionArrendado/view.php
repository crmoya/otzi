<?php
$this->menu=array(
	array('label'=>'Crear camión, camioneta, auto Arrendado', 'url'=>array('create')),
	array('label'=>'Editar camión, camioneta, auto Arrendado', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar camión, camioneta, auto Arrendado', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar camiones, camionetas, autos Arrendados', 'url'=>array('admin')),
);
?>

<h1>Ver camión, camioneta, auto Arrendado #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'capacidad',
		'pesoOVolumen',
		'consumoPromedio',
		'produccionMinima',
		'horasMin',
		'coeficienteDeTrato',
		'vigente',
	),
)); ?>
