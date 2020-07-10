<?php
/* @var $this EpObraController */
/* @var $model EpObra */

$this->breadcrumbs=array(
	'Ep Obras'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EpObra', 'url'=>array('index')),
	array('label'=>'Create EpObra', 'url'=>array('create')),
	array('label'=>'View EpObra', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EpObra', 'url'=>array('admin')),
);
?>

<h1>Update EpObra <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>