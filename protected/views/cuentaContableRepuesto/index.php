<?php

$this->menu=array(
	array('label'=>'Crear Cuenta', 'url'=>array('create')),
	array('label'=>'Administrar Cuentas', 'url'=>array('admin')),
);
?>

<h1>Cuentas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
