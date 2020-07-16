<?php

$this->menu=array(
	array('label'=>'Administrar Cuentas', 'url'=>array('admin')),
);
?>

<h1>Crear Cuenta</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>