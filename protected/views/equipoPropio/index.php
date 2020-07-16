<?php
$this->menu=array(
	array('label'=>'Crear Equipo Propio', 'url'=>array('create')),
	array('label'=>'Administrar Equipos Propios', 'url'=>array('admin')),
);
?>

<h1>Equipos Propios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
