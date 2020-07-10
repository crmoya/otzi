<?php
/* @var $this ContratosAdjudicadosController */
/* @var $model ContratosAdjudicados */

$this->breadcrumbs=array(
	'Menú Informes'=>array('/site/informes'),
	'Informe contratos adjudicados',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('contratos-adjudicados-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Informe Contratos Adjudicados</h1>

<p>
Si desea puede insertar los operadores de comparación (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) al comienzo de cada búsqueda para especificar como la consulta debe ser hecha.
</p>
<div class="vinculos">
<?php echo CHtml::link('Exportar a Excel', Yii::app()->createUrl("contratosAdjudicados/exportar",array()))?>
<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'contratos-adjudicados-grid',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		'nombre',
                'rut_mandante',
                'nombre_mandante',            
		'plazo',
		array(            
            'name'=>'fecha_inicio',
            'value'=>array($model,'gridDataColumnFechaInicio'),
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'fecha_inicio', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_fecha_inicio',
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
		array(            
            'name'=>'fecha_termino',
            'value'=>array($model,'gridDataColumnFechaTermino'),
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'fecha_termino', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_fecha_termino',
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
        array(
        	'name'=>'monto_inicial_neto',
			'value'=>'Yii::app()->format->number($data->monto_inicial_neto)'
		),
		array(
        	'name'=>'modificaciones_neto',
			'value'=>'Yii::app()->format->number($data->modificaciones_neto)'
		),
		array(
        	'name'=>'monto_actualizado_neto',
			'value'=>'Yii::app()->format->number($data->monto_actualizado_neto)'
		),
		array(
        'name'  => 'totales',
        'value' => 'CHtml::link(Yii::app()->format->number($data->totales), Yii::app()->createUrl("contratosAdjudicados/verDetalle",array("id"=>$data->id)))',
        'type'  => 'raw',
    	),
    	array(
        	'name'=>'diferencia_por_cobrar',
			'value'=>'Yii::app()->format->number($data->diferencia_por_cobrar)'
		),
		'observacion',
	),
));

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_fecha_inicio').datepicker($.datepicker.regional[ 'es' ]);
	$('#datepicker_for_fecha_termino').datepicker($.datepicker.regional[ 'es' ]);
}
");

 ?>
