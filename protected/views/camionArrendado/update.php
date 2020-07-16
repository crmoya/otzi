<?php
$this->menu=array(
	array('label'=>'Crear Camión Arrendado', 'url'=>array('create')),
	array('label'=>'Ver Camión Arrendado', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar camiones, camionetas, autos Arrendados', 'url'=>array('admin')),
);
?>

<h1>Editar camión, camioneta, auto Arrendado <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>