<?php
$this->menu=array(
	array('label'=>'Crear Unidad', 'url'=>array('create')),
	array('label'=>'Administrar Unidades', 'url'=>array('admin')),
);
?>

<h1>Unidades</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
