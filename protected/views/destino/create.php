<?php

$this->menu=array(
	array('label'=>'Administrar Destinos', 'url'=>array('admin')),
);
?>

<h1>Crear Destino</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>