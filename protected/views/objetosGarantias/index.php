<?php
$this->menu=array(
	array('label'=>'Crear Objeto de Garantía', 'url'=>array('create')),
	array('label'=>'Administrar Objetos de Garantía', 'url'=>array('admin')),
);
?>
<h1>Instituciones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
