<?php
$this->menu=array(
	array('label'=>'Crear Tipo de Combustible', 'url'=>array('create')),
	array('label'=>'Administrar Tipo de Combustible', 'url'=>array('admin')),
);
?>

<h1>Tipos de Combustibles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
