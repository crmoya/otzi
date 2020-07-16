<?php
$this->breadcrumbs=array(
	'Rcamion Arrendados'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RCamionArrendado', 'url'=>array('index')),
	array('label'=>'Manage RCamionArrendado', 'url'=>array('admin')),
);
?>

<h1>Create RCamionArrendado</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>