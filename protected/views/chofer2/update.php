<?php
$this->menu=array(
	array('label'=>'Crear Chofer', 'url'=>array('create')),
	array('label'=>'Ver Chofer', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Choferes', 'url'=>array('admin')),
);
?>

<h1>Editar Chofer <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>