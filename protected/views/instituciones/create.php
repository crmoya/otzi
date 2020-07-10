<?php
$this->menu=array(
	array('label'=>'Administrar Instituciones', 'url'=>array('admin')),
);
?>
<h1>Crear Institución</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>