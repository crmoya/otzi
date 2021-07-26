<?php
$this->menu=array(
	array('label'=>'Administrar camiones, camionetas, autos Propios', 'url'=>array('admin')),
);
?>

<h1>Crear camiones, camionetas, autos Propios</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>