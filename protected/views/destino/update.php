<?php
$this->menu=array(
	array('label'=>'Crear Destino', 'url'=>array('create')),
	array('label'=>'Ver Destino', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Destinos', 'url'=>array('admin')),
);
?>

<h1>Editar Destino <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>