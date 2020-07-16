<?php
$this->breadcrumbs=array(
	'Origendestino Faenas',
);

$this->menu=array(
	array('label'=>'Create OrigendestinoFaena', 'url'=>array('create')),
	array('label'=>'Manage OrigendestinoFaena', 'url'=>array('admin')),
);
?>

<h1>Origendestino Faenas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
