<?php
$this->menu=array(
	array('label'=>'Administrar Tipos de Garantía', 'url'=>array('admin')),
);
?>

<h1>Crear Tipo de Garantía</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>