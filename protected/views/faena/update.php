<?php

$this->menu=array(
	array('label'=>'Crear Faena (medida por volumen)', 'url'=>array('createv')),
	array('label'=>'Ver Faena (medida por volumen)', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Faenas (medidas por volumen)', 'url'=>array('adminv')),
);


?>


<?php if(Yii::app()->user->hasFlash('errorGrabarFaena')): ?>
<div class="flash-error">
	<?php echo Yii::app()->user->getFlash('errorGrabarFaena'); ?>
</div>
<?php endif;?>
<h1>Editar Faena <?php echo $model->id; ?></h1>

<?=$this->renderPartial('_formv', array('model'=>$model,'ods'=>$ods)); ?>