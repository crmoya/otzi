<?php
$this->menu=array(
	array('label'=>'Administrar camiones, camionetas, autos Arrendados', 'url'=>array('admin')),
);
?>

<h1>Crear camión, camioneta, auto Arrendado</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>