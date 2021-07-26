<?php
$this->menu=array(
	array('label'=>'Crear camión, camioneta, auto Propio', 'url'=>array('create')),
	array('label'=>'Editar camión, camioneta, auto Propio', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar camión, camioneta, auto Propio', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar camiones, camionetas, autos Propio', 'url'=>array('admin')),
);
?>

<h1>Ver camión, camioneta, auto Propio #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'codigo',
		'capacidad',
		'pesoOVolumen',
		'consumoPromedio',
		'produccionMinima',
		'horasMin',
		'coeficienteDeTrato',
		['name'=>'odometro_en_millas','value'=>$model->odometro_en_millas?"Sí":"No"],
		'vigente',
	),
)); ?>
