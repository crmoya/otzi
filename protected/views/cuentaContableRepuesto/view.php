<?php
$this->menu=array(
	array('label'=>'Crear Cuenta', 'url'=>array('create')),
	array('label'=>'Editar Cuenta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Cuenta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Cuentas', 'url'=>array('admin')),
);
?>

<h1>Ver Cuenta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
                'nombre',
	),
)); ?>
