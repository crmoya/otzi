<?php

$this->menu=array(
	array('label'=>'Administrar Propietario', 'url'=>array('admin')),
);
?>

<h1>Crear Propietario</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>