<?php
$this->breadcrumbs=array(
	'Requipo Arrendados'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List REquipoArrendado', 'url'=>array('index')),
	array('label'=>'Manage REquipoArrendado', 'url'=>array('admin')),
);
?>

<h1>Create REquipoArrendado</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>