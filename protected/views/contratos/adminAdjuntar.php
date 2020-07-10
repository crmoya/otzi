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

<h1>Digitalizaciones y Archivos de un Contrato, Resolución o Garantía</h1>

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
	'dataProvider'=>$model->searchNoCerradosUsuarioActual(),
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
		    'template'=>'{cerrar} {resolucion} {garantia} {libros}',
		    'buttons'=>array
		    (
		        'cerrar' => array
		        (
		            'label'=>'Digitalizaciones y Archivos de Contrato',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/adjuntoChico.png',
		            'url'=>'Yii::app()->createUrl("contratos/adjuntar", array("id"=>$data->contratos_id))',
		        ),
		        'resolucion' => array
		        (
		            'label'=>'Digitalizaciones, Resoluciones, OC y Contratos de mandante hacia nosotros',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/resolucion.jpg',
		            'url'=>'Yii::app()->createUrl("contratos/resolucionesAdj", array("id"=>$data->contratos_id))',
		        ),
		        'garantia' => array
		        (
		            'label'=>'Digitalizaciones y Archivos de Garantía de Contrato',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/garantias.jpg',
		            'url'=>'Yii::app()->createUrl("contratos/garantias", array("id"=>$data->contratos_id))',
		        ),
	    		'libros' => array
	    		(
    				'label'=>'Libros de obra, comunicaciones y otros',
    				'imageUrl'=>Yii::app()->request->baseUrl.'/images/libro.png',
    				'url'=>'Yii::app()->createUrl("contratos/libros", array("id"=>$data->contratos_id))',
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
