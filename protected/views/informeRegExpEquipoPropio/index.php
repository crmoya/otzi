<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Propios',
);

$this->menu=array(
	array('label'=>'Create InformeRegExpEquipoPropio', 'url'=>array('create')),
	array('label'=>'Manage InformeRegExpEquipoPropio', 'url'=>array('admin')),
);
?>

<h1>Informe Reg Exp Equipo Propios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
