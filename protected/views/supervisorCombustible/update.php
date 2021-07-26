<?php
$this->menu=array(
	array('label'=>'Crear Supervisor de Combustible', 'url'=>array('create')),
	array('label'=>'Ver Supervisor de Combustible', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Supervisores de Combustible', 'url'=>array('admin')),
);
?>

<h1>Editar Supervisor de Combustible <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>