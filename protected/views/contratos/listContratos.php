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
<table>
    <tr>
        <td><h1>Administrar Contratos</h1></td>
        <td><a href='<?=CController::createUrl('//contratos/exportarAdmin'); ?>'>Exportar a Excel</a></td>
    </tr>
</table>

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
	'dataProvider'=>$model->searchAdmin(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'ajaxUpdate'=>'false',
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
		array('name'=>'estado_contrato', 'value'=>'$data->estadosContratos->nombre'),
                array('name'=>'rut_mandante'),
                array('name'=>'nombre_mandante'),
		array(
		    'class'=>'CButtonColumn',
			'header'=>'Acción',
		    'template'=>'{editar} {editar garantias} {visualizar} {delete}',
		    'buttons'=>array
		    (
		        'editar' => array
		        (
		            'label'=>'Editar este contrato',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/edit.png',
		            'url'=>'Yii::app()->createUrl("contratos/edit", array("id"=>$data->id))',
		        ),
		    	'editar garantias' => array
		    	(
		    		'label'=>'Editar Garantías asociadas a contrato',
		    		'imageUrl'=>Yii::app()->request->baseUrl.'/images/garantias.jpg',
		    		'url'=>'Yii::app()->createUrl("garantias/admin", array("id"=>$data->id))',
		    	),
		        'visualizar' => array
		        (
		            'label'=>'Ver este contrato',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/lupa.png',
		            'url'=>'Yii::app()->createUrl("contratos/view", array("id"=>$data->id))',
		        ),
	    		'delete' => array
	    		(
	    				'label'=>'Eliminar este contrato',
	    				'imageUrl'=>Yii::app()->request->baseUrl.'/images/eliminar.png',
	    				'url'=>'Yii::app()->createUrl("contratos/delete", array("id"=>$data->id))',
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
