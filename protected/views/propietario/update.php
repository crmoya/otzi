<?php

$this->menu=array(
	array('label'=>'Crear Propietario', 'url'=>array('create')),
	array('label'=>'Ver Propietario', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Propietarios', 'url'=>array('admin')),
);
?>

<h1>Editar Propietario <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>