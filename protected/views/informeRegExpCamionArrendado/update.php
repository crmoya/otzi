<?php
/* @var $this InformeregexpcamionarrendadoController */
/* @var $model Informeregexpcamionarrendado */

$this->breadcrumbs=array(
	'Informeregexpcamionarrendados'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Informeregexpcamionarrendado', 'url'=>array('index')),
	array('label'=>'Create Informeregexpcamionarrendado', 'url'=>array('create')),
	array('label'=>'View Informeregexpcamionarrendado', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Informeregexpcamionarrendado', 'url'=>array('admin')),
);
?>

<h1>Update Informeregexpcamionarrendado <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>