<?php
$this->breadcrumbs=array(
	'Rcamion Arrendados'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RCamionArrendado', 'url'=>array('index')),
	array('label'=>'Create RCamionArrendado', 'url'=>array('create')),
	array('label'=>'View RCamionArrendado', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RCamionArrendado', 'url'=>array('admin')),
);
?>

<h1>Update RCamionArrendado <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>