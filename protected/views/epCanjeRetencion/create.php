<?php
/* @var $this EpCanjeRetencionController */
/* @var $model EpCanjeRetencion */

$this->breadcrumbs=array(
	'Ep Canje Retencions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EpCanjeRetencion', 'url'=>array('index')),
	array('label'=>'Manage EpCanjeRetencion', 'url'=>array('admin')),
);
?>

<h1>Create EpCanjeRetencion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>