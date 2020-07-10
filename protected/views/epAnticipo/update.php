<?php
/* @var $this EpAnticipoController */
/* @var $model EpAnticipo */

$this->breadcrumbs=array(
	'Ep Anticipos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EpAnticipo', 'url'=>array('index')),
	array('label'=>'Create EpAnticipo', 'url'=>array('create')),
	array('label'=>'View EpAnticipo', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EpAnticipo', 'url'=>array('admin')),
);
?>

<h1>Update EpAnticipo <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>