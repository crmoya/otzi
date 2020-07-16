<?php
$this->menu=array(
	array('label'=>'Crear Centro de Gestión', 'url'=>array('create')),
	array('label'=>'Administrar Centro de Gestión', 'url'=>array('admin')),
);
?>

<h1>Centros de Gestión</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
