<?php
/* @var $this EpAnticipoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ep Anticipos',
);

$this->menu=array(
	array('label'=>'Create EpAnticipo', 'url'=>array('create')),
	array('label'=>'Manage EpAnticipo', 'url'=>array('admin')),
);
?>

<h1>Ep Anticipos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
