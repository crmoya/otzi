<?php
/* @var $this GarantiasController */
/* @var $model Garantias */

$this->breadcrumbs=array(
	'Garantiases'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Garantias', 'url'=>array('index')),
	array('label'=>'Manage Garantias', 'url'=>array('admin')),
);
?>

<h1>Create Garantias</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>