<?php
$this->menu=array(
	array('label'=>'Crear Unidad', 'url'=>array('create')),
	array('label'=>'Ver Unidad', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Unidades', 'url'=>array('admin')),
);
?>

<h1>Editar Unidad <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>