<?php

$this->menu=array(
	array('label'=>'Crear Equipo Arrendado', 'url'=>array('create')),
	array('label'=>'Editar Equipo Arrendado', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Equipo Arrendado', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Equipos Arrendados', 'url'=>array('admin')),
);
?>

<h1>Ver Equipo Arrendado #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'horasMin',
		'precioUnitario',
		'propietarios.nombre',
		'valorHora',
		'consumoEsperado',
		'coeficienteDeTrato',
		'vigente',
	),
)); ?>
