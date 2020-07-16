<?php

$this->menu=array(
	array('label'=>'Crear Propietario', 'url'=>array('create')),
	array('label'=>'Administrar Propietarios', 'url'=>array('admin')),
);
?>

<h1>Propietarios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
