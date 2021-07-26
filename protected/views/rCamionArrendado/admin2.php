<h4>Registros de Expediciones de camiones, camionetas, autos Arrendados</h4>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'rcamion-arrendado-grid',
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
                'observaciones_obra',
		array('name'=>'camion', 'value'=>'$data->camiones!=null?$data->camiones->nombre:""'),
		array(
			'class'=>'CACustomButtonOP',
                        'template'=>'{update}',
                        'header'=>'Modificar',
                        'buttons'=>array
			(
                            'update' => array
                            (
                                'label'=>'Modificar Report',
                                'url'=>'($data->validado != 1)?Yii::app()->createUrl("//rCamionArrendado/update/$data->id"):""',
                            ),
			),
		),  
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_fecha').datepicker();
}
");
?>
