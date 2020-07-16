<?php
$this->menu=array(
	array('label'=>'Crear Equipo Propio', 'url'=>array('create')),
	array('label'=>'Ver Equipo Propio', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Equipos Propios', 'url'=>array('admin')),
);
?>

<h1>Editar Equipo Propio <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>