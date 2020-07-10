<?php
/* @var $this EpCanjeRetencionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ep Canje Retencions',
);

$this->menu=array(
	array('label'=>'Create EpCanjeRetencion', 'url'=>array('create')),
	array('label'=>'Manage EpCanjeRetencion', 'url'=>array('admin')),
);
?>

<h1>Ep Canje Retencions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
