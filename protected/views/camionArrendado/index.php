<?php
$this->menu=array(
	array('label'=>'Crear Camión Arrendado', 'url'=>array('create')),
	array('label'=>'Administrar camiones, camionetas, autos Arrendados', 'url'=>array('admin')),
);
?>

<h1>camiones, camionetas, autos Arrendados</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
