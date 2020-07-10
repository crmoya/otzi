<?php
/* @var $this TiposReajustesController */
/* @var $model TiposReajustes */

$this->breadcrumbs=array(
	'Tipos Reajustes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TiposReajustes', 'url'=>array('index')),
	array('label'=>'Manage TiposReajustes', 'url'=>array('admin')),
);
?>

<h1>Create TiposReajustes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>