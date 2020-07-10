<?php
$this->menu=array(
	array('label'=>'Crear Institución', 'url'=>array('create')),
	array('label'=>'Ver Institución', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Instituciones', 'url'=>array('admin')),
);
?>
<h1>Editar Institución <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>