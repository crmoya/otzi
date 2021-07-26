<?php
$this->breadcrumbs=array(
	'Requipo Arrendados'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List REquipoArrendado', 'url'=>array('index')),
	array('label'=>'Create REquipoArrendado', 'url'=>array('create')),
	array('label'=>'View REquipoArrendado', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage REquipoArrendado', 'url'=>array('admin')),
);
?>

<h1>Update REquipoArrendado <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>