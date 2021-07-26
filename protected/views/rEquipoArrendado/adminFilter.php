<h4>Registros de Expediciones de Equipos Arrendados</h4>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'requipo-arrendado-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
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
                )
            ), 
            true), 
        ),
		'reporte',
		'observaciones',
		array('name'=>'equipo', 'value'=>'$data->equipos->nombre'),
		array(
			'class'=>'ViewCButtonColumn',
		),
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_fecha').datepicker();
}
");
?>
