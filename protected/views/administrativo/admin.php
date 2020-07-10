<?php
/* @var $this AdministrativoController */
/* @var $model Administrativo */

$this->breadcrumbs=array(
	'Menú Informes'=>array('/site/informes'),
	'Informe Administrativo',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('administrativo-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Informe Administrativo</h1>

<p>
Si desea puede insertar los operadores de comparación (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) al comienzo de cada búsqueda para especificar como la consulta debe ser hecha.
</p>
<div class="vinculos">
<?php echo CHtml::link('Exportar a Excel', Yii::app()->createUrl("administrativo/exportar",array()))?>
<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'administrativo-grid',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array('name'=>'nombre_contrato','header'=>'Proyecto'),
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
		'numero_resolucion',
		'observacion',
		array(            
            'name'=>'fecha_final',
            'value'=>array($model,'gridDataColumnFechaFinal'),
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'fecha_final', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_fecha_final',
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
			'name'=>'suma_monto_contrato',
			'value'=>'Yii::app()->format->number($data->suma_monto_contrato)'
		),
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_mes').datepicker($.datepicker.regional[ 'es' ]);
	$('#datepicker_for_fecha_final').datepicker($.datepicker.regional[ 'es' ]);
}
");

?>
