<?php
/* @var $this FinancieroController */
/* @var $model Financiero */

$this->breadcrumbs=array(
	'Menú Informes'=>array('/site/informes'),
	'Informe financiero',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('financiero-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Informe Financiero</h1>

<p>
Si desea puede insertar los operadores de comparación (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) al comienzo de cada búsqueda para especificar como la consulta debe ser hecha.
</p>
<div class="vinculos">
<?php echo CHtml::link('Exportar a Excel', Yii::app()->createUrl("financiero/exportar",array()))?>
<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'financiero-grid',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		'nombre_contrato',
                'rut_mandante',
                'nombre_mandante',
		array(            
            'name'=>'mes',
            'value'=>array($model,'gridDataColumnMes'),
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'mes', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_mes',
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
        'tipo',
        array(
			'name'=>'saldo_por_cobrar_retenciones',
			'value'=>'Yii::app()->format->number($data->saldo_por_cobrar_retenciones)'
		),
		array(
			'name'=>'venta_facturada_neta',
			'value'=>'Yii::app()->format->number($data->venta_facturada_neta)'
		),
		array(
			'name'=>'venta_facturada_acumulada_neta',
			'value'=>'Yii::app()->format->number($data->venta_facturada_acumulada_neta)'
		),
		array(
			'name'=>'costo',
			'value'=>'Yii::app()->format->number($data->costo)'
		), 
		 array(
			'name'=>'costo_acumulado',
			'value'=>'Yii::app()->format->number($data->costo_acumulado)'
		),
		array(
			'name'=>'resultado_mensual_neto',
			'value'=>'Yii::app()->format->number($data->resultado_mensual_neto)'
		),
		array(
			'name'=>'porc_rent_sobre_valor_contrato',
			'value'=>'Tools::cambiarSeparadorDecimal($data->porc_rent_sobre_valor_contrato)'
		),
		array(
			'name'=>'resultado_acumulado_neto',
			'value'=>'Yii::app()->format->number($data->resultado_acumulado_neto)'
		),
		array(
			'name'=>'porc_rent_sobre_valor_contrato_acum',
			'value'=>'Tools::cambiarSeparadorDecimal($data->porc_rent_sobre_valor_contrato_acum)'
		),
	),
)); 
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_mes').datepicker($.datepicker.regional[ 'es' ]);
}
");
?>
