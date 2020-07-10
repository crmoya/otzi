<?php
/* @var $this GarantiasController */
/* @var $model Garantias */

$this->breadcrumbs=array(
	'Garantiases'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Garantias', 'url'=>array('index')),
	array('label'=>'Create Garantias', 'url'=>array('create')),
	array('label'=>'View Garantias', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Garantias', 'url'=>array('admin')),
);
?>

<h1>Update Garantias <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>