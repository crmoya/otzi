<?php

$this->menu=array(
	array('label'=>'Crear Operador', 'url'=>array('create')),
	array('label'=>'Administrar Operadores', 'url'=>array('admin')),
);
?>

<h1>Operadores</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
