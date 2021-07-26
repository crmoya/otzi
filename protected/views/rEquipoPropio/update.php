<?php
$this->breadcrumbs=array(
	'Requipo Propios'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List REquipoPropio', 'url'=>array('index')),
	array('label'=>'Create REquipoPropio', 'url'=>array('create')),
	array('label'=>'View REquipoPropio', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage REquipoPropio', 'url'=>array('admin')),
);
?>

<h1>Update REquipoPropio <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>