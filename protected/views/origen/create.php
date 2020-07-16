<?php
$this->menu=array(
	array('label'=>'Administrar Orígenes', 'url'=>array('admin')),
);
?>

<h1>Crear Origen</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>