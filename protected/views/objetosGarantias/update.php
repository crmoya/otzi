<?php
$this->menu=array(
	array('label'=>'Crear Objeto de Garantía', 'url'=>array('create')),
	array('label'=>'Ver Objeto de Garantía', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Objetos de Garantía', 'url'=>array('admin')),
);
?>
<h1>Editar Objeto Garantía <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>