<?php
/* @var $this EpCanjeRetencionController */
/* @var $model EpCanjeRetencion */

$this->breadcrumbs=array(
	'Ep Canje Retencions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EpCanjeRetencion', 'url'=>array('index')),
	array('label'=>'Create EpCanjeRetencion', 'url'=>array('create')),
	array('label'=>'View EpCanjeRetencion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EpCanjeRetencion', 'url'=>array('admin')),
);
?>

<h1>Update EpCanjeRetencion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>