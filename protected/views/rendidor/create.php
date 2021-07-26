<?php
/* @var $this RendidorController */
/* @var $model Rendidor */


$this->menu=array(
	array('label'=>'Administrar Supervisor de Rendición', 'url'=>array('admin')),
);
?>

<h1>Crear Supervisor de Rendición</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>