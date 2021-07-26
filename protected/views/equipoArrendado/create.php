<?php

$this->menu=array(
	array('label'=>'Administrar Equipos Arrendados', 'url'=>array('admin')),
);
?>

<h1>Crear Equipo Arrendado</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>