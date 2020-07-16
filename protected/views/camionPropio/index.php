<?php
$this->menu=array(
	array('label'=>'Crear camión, camioneta, auto Propio', 'url'=>array('create')),
	array('label'=>'Administrar camión, camioneta, auto Propio', 'url'=>array('admin')),
);
?>

<h1>camiones, camionetas, autos Propios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
