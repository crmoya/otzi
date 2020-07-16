<?php
$this->menu=array(
	array('label'=>'Administrar Tipos de Combustible', 'url'=>array('admin')),
);
?>

<h1>Crear Tipo de Combustible</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>