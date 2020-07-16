<?php

$this->menu=array(
	array('label'=>'Crear Chofer', 'url'=>array('create')),
	array('label'=>'Administrar Choferes', 'url'=>array('admin')),
);
?>

<h1>Choferes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
