<?php

$this->menu=array(
	array('label'=>'Crear Faena', 'url'=>array('createv')),
	array('label'=>'Ver Faena', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Faenas', 'url'=>array('adminv')),
);


?>


<?php if(Yii::app()->user->hasFlash('errorGrabarFaena')): ?>
<div class="flash-error">
	<?php echo Yii::app()->user->getFlash('errorGrabarFaena'); ?>
</div>
<?php endif;?>
<h1>Editar Faena <?php echo $model->id; ?></h1>

<?=$this->renderPartial('_formv', array('model'=>$model,'ods'=>$ods)); ?>