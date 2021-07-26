<?php
$this->menu=array(
	array('label'=>'Crear Equipo Propio', 'url'=>array('create')),
	array('label'=>'Editar Equipo Propio', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Equipo Propio', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Equipos Propios', 'url'=>array('admin')),
);
?>

<h1>Ver Equipo Propio #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'codigo',
		'horasMin',
		'valorHora',
		'consumoEsperado',
		'coeficienteDeTrato',
		'vigente',
	),
)); ?>
