<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Propios'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InformeRegExpEquipoPropio', 'url'=>array('index')),
	array('label'=>'Create InformeRegExpEquipoPropio', 'url'=>array('create')),
	array('label'=>'View InformeRegExpEquipoPropio', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InformeRegExpEquipoPropio', 'url'=>array('admin')),
);
?>

<h1>Update InformeRegExpEquipoPropio <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>