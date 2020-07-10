<?php
$this->menu=array(
	array('label'=>'Crear Institución', 'url'=>array('create')),
	array('label'=>'Administrar Instituciones', 'url'=>array('admin')),
);
?>
<h1>Instituciones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
