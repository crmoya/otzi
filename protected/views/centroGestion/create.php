<?php
$this->menu=array(
	array('label'=>'Administrar Centros de Gestión', 'url'=>array('admin')),
);
?>

<h1>Crear Centro de Gestión</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>