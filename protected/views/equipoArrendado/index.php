<?php
$this->menu=array(
	array('label'=>'Crear Equipo Arrendado', 'url'=>array('create')),
	array('label'=>'Administrar Equipos Arrendados', 'url'=>array('admin')),
);
?>

<h1>Equipos Arrendados</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
