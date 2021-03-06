<h3>Detalle de Gasto de Combustible</h3>  
<?php echo CHtml::link('Volver','../gastoCombustible'); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo CHtml::link('Exportar Informe a Excel','../exportarDetalleGastoCombustible'); ?>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'columns'=>array(
        array(            
			'name'=>'fecha',
	        'value'=>array($model,'gridDataColumn'),
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		        'model'=>$model, 
		        'attribute'=>'fecha', 
		        'language' => 'es',
				'i18nScriptFile' => 'jquery.ui.datepicker-es.js', // (#2)
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
		        	),
		        ), 
	        true), 
	    ),  
        'reporte',
		'operario',
		'maquina',
		'petroleoLts',
	    'kmCarguio',
	    'guia',
	    'factura',
	    'precioUnitario',
	    'valorTotal',
	    'faena',
	    'tipoCombustible',
	    'supervisorCombustible',
		'numero',
    ),
));

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_fecha').datepicker();
}
");