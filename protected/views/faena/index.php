<?php

$this->menu=array(
	array('label'=>'Crear Faena', 'url'=>array('create')),
	array('label'=>'Administrar Faenas', 'url'=>array('admin')),
);
?>

<h1>Faenas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
