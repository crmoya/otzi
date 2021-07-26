<?php
/* @var $this RendidorController */
/* @var $model Rendidor */


$this->menu=array(
	array('label'=>'Crear Supervisor de Rendición', 'url'=>array('create')),
	array('label'=>'Administrar Supervisor de Rendición', 'url'=>array('admin')),
);
?>

<h1>Editar Supervisor de Rendición <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>