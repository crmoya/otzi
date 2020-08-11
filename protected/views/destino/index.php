<?php
$this->menu=array(
	array('label'=>'Crear Destino', 'url'=>array('create')),
	array('label'=>'Administrar Destinos', 'url'=>array('admin')),
);
?>

<h1>Destinos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
