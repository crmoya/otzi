<?php
/* @var $this TiposContratosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tipos Contratoses',
);

$this->menu=array(
	array('label'=>'Create TiposContratos', 'url'=>array('create')),
	array('label'=>'Manage TiposContratos', 'url'=>array('admin')),
);
?>

<h1>Tipos Contratoses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
