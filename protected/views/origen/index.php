<?php
$this->menu=array(
	array('label'=>'Crear Origen', 'url'=>array('create')),
	array('label'=>'Administrar Orígenes', 'url'=>array('admin')),
);
?>

<h1>Orígenes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
