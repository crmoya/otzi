<?php

$this->menu=array(
	array('label'=>'Administrar Faenas', 'url'=>array('adminv')),
);

?>

<?php if(Yii::app()->user->hasFlash('errorGrabarFaena')): ?>
<div class="flash-error">
	<?php echo Yii::app()->user->getFlash('errorGrabarFaena'); ?>
</div>
<?php endif;?>
<h1>Crear Faena</h1>

<?php echo $this->renderPartial('_formv', array('model'=>$model)); ?>