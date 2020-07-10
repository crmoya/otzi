<?php
/* @var $this EpAnticipoController */
/* @var $model EpAnticipo */

$this->breadcrumbs=array(
	'Ep Anticipos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EpAnticipo', 'url'=>array('index')),
	array('label'=>'Manage EpAnticipo', 'url'=>array('admin')),
);
?>

<h1>Create EpAnticipo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>