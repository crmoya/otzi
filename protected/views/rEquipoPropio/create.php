<?php
$this->breadcrumbs=array(
	'Requipo Propios'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List REquipoPropio', 'url'=>array('index')),
	array('label'=>'Manage REquipoPropio', 'url'=>array('admin')),
);
?>

<h1>Create REquipoPropio</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>