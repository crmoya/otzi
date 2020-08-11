<?php
$this->breadcrumbs=array(
	'Requipo Propios',
);

$this->menu=array(
	array('label'=>'Create REquipoPropio', 'url'=>array('create')),
	array('label'=>'Manage REquipoPropio', 'url'=>array('admin')),
);
?>

<h1>Requipo Propios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
