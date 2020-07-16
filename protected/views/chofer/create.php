<?php

$this->menu=array(
	array('label'=>'Administrar Choferes', 'url'=>array('admin')),
);
?>

<h1>Crear Chofer</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>