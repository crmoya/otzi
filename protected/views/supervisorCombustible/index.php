<?php
$this->menu=array(
	array('label'=>'Crear Supervisor de Combustible', 'url'=>array('create')),
	array('label'=>'Administrar Supervisores de Combustible', 'url'=>array('admin')),
);
?>

<h1>Supervisores de Combustible</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
