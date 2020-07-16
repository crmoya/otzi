<?php
/* @var $this InformeregexpcamionarrendadoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Informeregexpcamionarrendados',
);

$this->menu=array(
	array('label'=>'Create Informeregexpcamionarrendado', 'url'=>array('create')),
	array('label'=>'Manage Informeregexpcamionarrendado', 'url'=>array('admin')),
);
?>

<h1>Informeregexpcamionarrendados</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
