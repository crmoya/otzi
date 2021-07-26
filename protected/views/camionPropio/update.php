<?php
$this->menu=array(
	array('label'=>'Crear camión, camioneta, auto Propio', 'url'=>array('create')),
	array('label'=>'Ver camión, camioneta, auto Propio', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar camión, camioneta, auto Propio', 'url'=>array('admin')),
);
?>

<h1>Editar camión, camioneta, auto Propio <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>