<?php
$this->menu=array(
	array('label'=>'Administrar Equipos Propios', 'url'=>array('admin')),
);
?>

<h1>Crear Equipo Propio</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>