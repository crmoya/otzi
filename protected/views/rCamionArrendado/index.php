<?php
$this->breadcrumbs=array(
	'Rcamion Arrendados',
);

$this->menu=array(
	array('label'=>'Create RCamionArrendado', 'url'=>array('create')),
	array('label'=>'Manage RCamionArrendado', 'url'=>array('admin')),
);
?>

<h1>Rcamion Arrendados</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
