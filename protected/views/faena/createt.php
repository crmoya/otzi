<?php

$this->menu=array(
	array('label'=>'Administrar Faenas (medidas por tiempo)', 'url'=>array('admint')),
);
?>

<?php if(Yii::app()->user->hasFlash('errorGrabarFaena')): ?>
<div class="flash-error">
	<?php echo Yii::app()->user->getFlash('errorGrabarFaena'); ?>
</div>
<?php endif;?>
<h1>Crear Faena (medida por tiempo)</h1>

<?php echo $this->renderPartial('_formt', array('model'=>$model)); ?>