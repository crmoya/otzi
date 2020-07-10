<?php
/* @var $this InformeGarantiasController */
/* @var $model InformeGarantias */

$this->breadcrumbs=array(
	'Menú Informes'=>array('/site/informes'),
	'Informe garantías',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('informe-garantias-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Informe de Garantías</h1>

<p>
Si desea puede insertar los operadores de comparación (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) al comienzo de cada búsqueda para especificar como la consulta debe ser hecha.
</p>
<div class="vinculos">
<?php echo CHtml::link('Exportar a Excel', Yii::app()->createUrl("informeGarantias/exportar",array()))?>
<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'informe-garantias-grid',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		'numero',
		'tipo_garantia',
		'institucion',
		'monto',
		'moneda',
		'contrato',
		'objeto_garantia',
		array(            
            'name'=>'fecha_vencimiento',
            'value'=>array($model,'gridDataColumnFechaVencimiento'),
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'fecha_vencimiento', 
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
		'estado',
		array(
				'name'=>'fecha_devolucion',
				'value'=>array($model,'gridDataColumnFechaDevolucion'),
				'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'fecha_devolucion',
						'language' => 'es',
						'htmlOptions' => array(
								'id' => 'datepicker_for_fecha2',
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
	),
));

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_fecha').datepicker($.datepicker.regional[ 'es' ]);
}
function reinstallDatePicker2(id, data) {
	$('#datepicker_for_fecha2').datepicker($.datepicker.regional[ 'es' ]);
}
");

 ?>
