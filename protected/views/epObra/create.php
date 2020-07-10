<?php
/* @var $this EpObraController */
/* @var $model EpObra */

$this->breadcrumbs=array(
	'Ep Obras'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EpObra', 'url'=>array('index')),
	array('label'=>'Manage EpObra', 'url'=>array('admin')),
);
?>

<h1>Create EpObra</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>