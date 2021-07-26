<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Arrendados'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InformeRegExpEquipoArrendado', 'url'=>array('index')),
	array('label'=>'Manage InformeRegExpEquipoArrendado', 'url'=>array('admin')),
);
?>

<h1>Create InformeRegExpEquipoArrendado</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>