<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

$this->breadcrumbs=array(
	'Gasto Completas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GastoCompleta', 'url'=>array('index')),
	array('label'=>'Manage GastoCompleta', 'url'=>array('admin')),
);
?>

<h1>Create GastoCompleta</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>