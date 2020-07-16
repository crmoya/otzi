<?php
$this->menu=array(
	array('label'=>'Crear Centro de Gestión', 'url'=>array('create')),
	array('label'=>'Ver Centro de Gestión', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Centros de Gestión', 'url'=>array('admin')),
);
?>

<h1>Editar Centro de Gestión <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>