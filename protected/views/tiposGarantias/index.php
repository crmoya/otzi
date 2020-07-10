<?php
$this->menu=array(
	array('label'=>'Crear Tipo de Garantía', 'url'=>array('create')),
	array('label'=>'Administrar Tipos de Garantía', 'url'=>array('admin')),
);
?>

<h1>Tipo de Garantía</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
