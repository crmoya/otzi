<?php
$this->menu=array(
	array('label'=>'Crear Operador', 'url'=>array('create')),
	array('label'=>'Ver Operador', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Operadores', 'url'=>array('admin')),
);
?>

<h1>Editar Operador <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>