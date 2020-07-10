<?php
/* @var $this EpObraController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ep Obras',
);

$this->menu=array(
	array('label'=>'Create EpObra', 'url'=>array('create')),
	array('label'=>'Manage EpObra', 'url'=>array('admin')),
);
?>

<h1>Ep Obras</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
