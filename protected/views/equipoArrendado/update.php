<?php
$this->menu=array(
	array('label'=>'Crear Equipo Arrendado', 'url'=>array('create')),
	array('label'=>'Ver Equipo Arrendado', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Equipos Arrendados', 'url'=>array('admin')),
);
?>

<h1>Editar Equipo Arrendado <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>