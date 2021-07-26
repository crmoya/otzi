<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Arrendados'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InformeRegExpEquipoArrendado', 'url'=>array('index')),
	array('label'=>'Create InformeRegExpEquipoArrendado', 'url'=>array('create')),
	array('label'=>'View InformeRegExpEquipoArrendado', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InformeRegExpEquipoArrendado', 'url'=>array('admin')),
);
?>

<h1>Update InformeRegExpEquipoArrendado <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>