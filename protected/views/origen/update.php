<?php
$this->menu=array(
	array('label'=>'Crear Origen', 'url'=>array('create')),
	array('label'=>'Ver Origen', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Orígenes', 'url'=>array('admin')),
);
?>

<h1>Editar Origen <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>