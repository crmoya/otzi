<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

$this->breadcrumbs=array(
	'Gasto Completas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GastoCompleta', 'url'=>array('index')),
	array('label'=>'Create GastoCompleta', 'url'=>array('create')),
	array('label'=>'View GastoCompleta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GastoCompleta', 'url'=>array('admin')),
);
?>

<h1>Update GastoCompleta <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>