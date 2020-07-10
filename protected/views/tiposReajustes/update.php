<?php
/* @var $this TiposReajustesController */
/* @var $model TiposReajustes */

$this->breadcrumbs=array(
	'Tipos Reajustes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TiposReajustes', 'url'=>array('index')),
	array('label'=>'Create TiposReajustes', 'url'=>array('create')),
	array('label'=>'View TiposReajustes', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TiposReajustes', 'url'=>array('admin')),
);
?>

<h1>Update TiposReajustes <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>