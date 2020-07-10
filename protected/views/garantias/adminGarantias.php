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

<h1>Seleccione contrato para agregar Garantía</h1>

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
	'dataProvider'=>$model->searchAdjudicadosUsuarioActual(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		'nombre',
		array(            
            'name'=>'fecha_inicio',
            'value'=>array($model,'gridDataColumn'),
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'fecha_inicio', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_fecha',
                    'size' => '10',
                ),
                'defaultOptions' => array(  // (#3)
                    'showOn' => 'focus', 
                    'dateFormat' => 'dd/mm/yy',
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => true,
                )
            ), 
            true), 
        ),
		'observacion',
		array('name'=>'estados_contratos_nombre'),
                array('name'=>'rut_mandante'),
                array('name'=>'nombre_mandante'),
		array(
		    'class'=>'CButtonColumn',
		    'template'=>'{editar}',
		    'buttons'=>array
		    (
		        'editar' => array
		        (
		            'label'=>'Agregar garantía',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/garantias.jpg',
		            'url'=>'Yii::app()->createUrl("//garantias/agregar", array("id"=>$data->contratos_id))',
		        ),
		    ),
		),
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_fecha').datepicker($.datepicker.regional[ 'es' ]);
}
");
?>
