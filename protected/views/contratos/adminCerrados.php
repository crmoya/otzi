<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('destino-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Reabrir un Contrato</h1>

<?php if(Yii::app()->user->hasFlash('adminMessage')): ?>
<div class="flash-success">
<?php echo Yii::app()->user->getFlash('adminMessage'); ?>
</div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('adminError')): ?>
<div class="flash-error">
<?php echo Yii::app()->user->getFlash('adminError'); ?>
</div>
<?php endif; ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'destino-grid',
	'dataProvider'=>$model->searchCerrados(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		'nombre',
		array('name'=>'estados_contratos_nombre'),
		array(
		    'class'=>'CButtonColumn',
		    'template'=>'{cerrar}',
		    'buttons'=>array
		    (
		        'cerrar' => array
		        (
		            'label'=>'Reabrir este Contrato',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/ok.png',
		            'url'=>'Yii::app()->createUrl("contratos/reabrir", array("id"=>$data->contratos_id))',
		        ),
		    ),
		),
	),
)); 

?>
