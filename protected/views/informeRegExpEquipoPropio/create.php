<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Propios'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InformeRegExpEquipoPropio', 'url'=>array('index')),
	array('label'=>'Manage InformeRegExpEquipoPropio', 'url'=>array('admin')),
);
?>

<h1>Create InformeRegExpEquipoPropio</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>