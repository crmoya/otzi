<?php

Yii::app()->getController()->pageTitle="Gastos de " . $gastoNombre;

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('chofer-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h3>Registros de gastos de <?=$gastoNombre?></h3>

<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
	'policy'=>$model->policy,
)); ?>
</div><!-- search-form -->


<?php echo $this->renderPartial('//tables/_header'); ?>

<?php
$datos = GastoCompleta::model()->findAll($model->search());
echo $this->renderPartial('//tables/_cuerpo',['datos'=>$datos, 'cabeceras' => $cabeceras, 'extra_datos'=>$extra_datos]);
?>
<?php echo $this->renderPartial('//tables/_footer',['extra_datos'=>$extra_datos]); ?>