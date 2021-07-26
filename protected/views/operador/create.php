<?php

$this->menu=array(
	array('label'=>'Administrar Operadores', 'url'=>array('admin')),
);
?>

<h1>Crear Operador</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>