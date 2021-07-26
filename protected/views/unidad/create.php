<?php

$this->menu=array(
	array('label'=>'Administrar Unidades', 'url'=>array('admin')),
);
?>

<h1>Crear Unidad</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>