<?php
/* @var $this InformeregexpcamionarrendadoController */
/* @var $model Informeregexpcamionarrendado */

$this->breadcrumbs=array(
	'Informeregexpcamionarrendados'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Informeregexpcamionarrendado', 'url'=>array('index')),
	array('label'=>'Manage Informeregexpcamionarrendado', 'url'=>array('admin')),
);
?>

<h1>Create Informeregexpcamionarrendado</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>