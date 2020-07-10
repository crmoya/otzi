<?php
$this->menu=array(
	array('label'=>'Administrar Objetos de Garantía', 'url'=>array('admin')),
);
?>
<h1>Crear Objeto de Garantía</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>