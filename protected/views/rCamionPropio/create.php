<?php
$this->breadcrumbs=array(
	'Rcamion Propios'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RCamionPropio', 'url'=>array('index')),
	array('label'=>'Manage RCamionPropio', 'url'=>array('admin')),
);
?>

<h1>Create RCamionPropio</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>