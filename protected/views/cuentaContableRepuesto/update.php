<?php
$this->menu=array(
	array('label'=>'Crear Cuenta', 'url'=>array('create')),
	array('label'=>'Ver Cuenta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Cuentas', 'url'=>array('admin')),
);
?>

<h1>Editar Cuenta <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>