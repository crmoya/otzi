<?php
$this->menu=array(
	array('label'=>'Crear Tipo de Garantía', 'url'=>array('create')),
	array('label'=>'Ver Tipo de Garantía', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Tipos de Garantía', 'url'=>array('admin')),
);
?>

<h1>Editar Tipo de Garantía <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>