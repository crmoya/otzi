<?php

$this->menu=array(
	array('label'=>'Crear Tipo de Combustible', 'url'=>array('create')),
	array('label'=>'Ver Tipo de Combustible', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Tipos de Combustible', 'url'=>array('admin')),
);
?>

<h1>Editar Tipo de Combustible <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>