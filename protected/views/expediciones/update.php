<?php
/* @var $this ExpedicionesController */
/* @var $model Expediciones */

$this->breadcrumbs=array(
	'Expediciones'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Expediciones', 'url'=>array('index')),
	array('label'=>'Create Expediciones', 'url'=>array('create')),
	array('label'=>'View Expediciones', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Expediciones', 'url'=>array('admin')),
);
?>

<h1>Update Expediciones <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>