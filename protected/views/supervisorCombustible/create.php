<?php
$this->menu=array(
	array('label'=>'Administrar Supervisores de Combustible', 'url'=>array('admin')),
);
?>

<h1>Crear Supervisor de Combustible</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>