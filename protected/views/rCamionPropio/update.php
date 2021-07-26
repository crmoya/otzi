<?php
$this->breadcrumbs=array(
	'Rcamion Propios'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RCamionPropio', 'url'=>array('index')),
	array('label'=>'Create RCamionPropio', 'url'=>array('create')),
	array('label'=>'View RCamionPropio', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RCamionPropio', 'url'=>array('admin')),
);
?>

<h1>Update RCamionPropio <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>