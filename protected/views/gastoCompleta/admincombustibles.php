<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

?>

<h1>Registros de gastos de <?=$gastoNombre?></h1>
<?php echo CHtml::link('Exportar a Excel','exportar?policy='.$model->policy); ?>
<?php 


Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_fecha').datepicker({
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    });
}
");


$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gasto-completa-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'columns'=>array(
		['name'=>'proveedor', 'value'=>'$data->supplier'],
		[
			'name' => 'fecha',
            'value' => array($model, 'gridDataColumn'),
            'filter' => $this->widget(
                'zii.widgets.jui.CJuiDatePicker',
                array(
                    'model' => $model,
                    'attribute' => 'fecha',
                    'language' => 'es',
                    'htmlOptions' => array(
                        'id' => 'datepicker_for_fecha',
                        'size' => '10',
                    ),
                    'defaultOptions' => array( 
                        'showOn' => 'focus',
                        'dateFormat' => 'dd/mm/yy',
                        'showOtherMonths' => true,
                        'selectOtherMonths' => true,
                        'changeMonth' => true,
                        'changeYear' => true,
                        'showButtonPanel' => true,
                    )
                ),
				true
			)
		],
		['name' => 'neto', 'value' => '"$".number_format($data->net,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'total', 'value' => '"$".number_format($data->tot,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name'=>'categoria', 'value'=>'$data->category'],
		['name'=>'grupocategoria', 'value'=>'$data->categorygroup'],
		['name'=>'nota', 'value'=>'$data->note'],
		//'retenido',
		'cantidad',
		'unidad',
		'centro_costo_faena',
		//'departamento',
		//'faena',
		//'impuesto_especifico',
		//'iva',
		//'km_carguio',
		//'litros_combustible',
		//'monto_neto',
		'nombre_quien_rinde',
		'nro_documento',
		//'periodo_planilla',
		'rut_proveedor',
		//'supervisor_combustible',
		'tipo_documento',
		'vehiculo_equipo',
		[
			'name' => 'folio',
            'type' => 'raw',
            'value'=>'CHtml::link($data->folioinforme, array("informeGasto/view", "id"=>$data->folioinforme))',
		],
		//'vehiculo_oficina_central',
		[
			'header' => 'Imagen',
			'type' => 'raw',
			'value'=>'($data->image!="SIN IMAGEN")?
				CHtml::link("<img src=\''.Yii::app()->request->baseUrl.'/images/search.png\'>", ($data->image),["target"=>"_blank"])
        		:"SIN IMAGEN"',
		],
	),
)); ?>
