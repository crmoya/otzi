<?php
$this->breadcrumbs=array(
	'Informe Reg Exp Equipo Arrendados',
);

$this->menu=array(
	array('label'=>'Create InformeRegExpEquipoArrendado', 'url'=>array('create')),
	array('label'=>'Manage InformeRegExpEquipoArrendado', 'url'=>array('admin')),
);
?>

<h1>Informe Reg Exp Equipo Arrendados</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
