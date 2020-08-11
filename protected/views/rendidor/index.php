<?php
/* @var $this RendidorController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
	array('label'=>'Crear Supervisor de Rendición', 'url'=>array('create')),
	array('label'=>'Administrar Supervisor de Rendición', 'url'=>array('admin')),
);
?>

<h1>Supervisores de Rendición</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
