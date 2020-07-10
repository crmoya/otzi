<?php
/* @var $this DatosController */
/* @var $model Datos */

$this->breadcrumbs=array(
	'Menú Informes'=>array('/site/informes'),
	'Informe Resumen de EP',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('datos-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Informe Resumen de EP</h1>

<p>
Si desea puede insertar los operadores de comparación (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) al comienzo de cada búsqueda para especificar como la consulta debe ser hecha.
</p>
<div class="vinculos">
<?php echo CHtml::link('Exportar a Excel', Yii::app()->createUrl("datos/exportar",array()))?>
<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'datos-grid',
	'dataProvider'=>$model->search(),
	'ajaxUpdate' => false,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		'nombre',
                array('name'=>'rut_mandante'),
                array('name'=>'nombre_mandante'),
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
			'name'=>'valor_contrato_neto',
			'value'=>'Yii::app()->format->number($data->valor_contrato_neto)'
		),
		array(
			'name'=>'anticipo',
			'value'=>'Yii::app()->format->number($data->anticipo)'
		),
		array(
			'name'=>'saldo_por_cobrar',
			'value'=>'Yii::app()->format->number($data->saldo_por_cobrar)'
		),
		array(
			'name'=>'reajuste',
			'value'=>'Yii::app()->format->number($data->reajuste)'
		),
		array(
			'name'=>'reajustes_acumulados',
			'value'=>'Yii::app()->format->number($data->reajustes_acumulados)'
		),
		array(
			'name'=>'retencion',
			'value'=>'Yii::app()->format->number($data->retencion)'
		),
		array(
			'name'=>'retenciones_acumuladas',
			'value'=>'Yii::app()->format->number($data->retenciones_acumuladas)'
		),
		array(
			'name'=>'descuento',
			'value'=>'Yii::app()->format->number($data->descuento)'
		),
		array(
			'name'=>'descuentos_acumulados',
			'value'=>'Yii::app()->format->number($data->descuentos_acumulados)'
		),
		array(
			'name'=>'produccion',
			'value'=>'Yii::app()->format->number($data->produccion)'
		),
		array(
			'name'=>'produccion_acumulada',
			'value'=>'Yii::app()->format->number($data->produccion_acumulada)'
		),
	),
));

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_mes').datepicker($.datepicker.regional[ 'es' ]);
}
");

 ?>
