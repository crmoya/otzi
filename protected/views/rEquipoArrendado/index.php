<?php
$this->breadcrumbs=array(
	'Requipo Arrendados',
);

$this->menu=array(
	array('label'=>'Create REquipoArrendado', 'url'=>array('create')),
	array('label'=>'Manage REquipoArrendado', 'url'=>array('admin')),
);
?>

<h1>Requipo Arrendados</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
