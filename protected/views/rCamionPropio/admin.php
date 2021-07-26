<h4>Registros de Expediciones de camiones, camionetas, autos Propios</h4>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'rcamion-propio-grid',
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
		array('name'=>'usuario', 'value'=>'$data->usuarios!=null?$data->usuarios->nombre:""'),
                array(
			'class'=>'MyCButtonColumn',
		),
            /*
                array(
			'class'=>'CPCustomButton',
			'template'=>'{destacar}',
			'header'=>'¿Validado?',
			'buttons'=>array
			(
					'destacar' => array
					(
                                            'label'=>'Validar Report',
                                            'url'=>'($data->validado != 1)?Yii::app()->createUrl("//rCamionPropio/validar/$data->id"):""',
					),
			),
		),
                array('name'=>'validador_nm', 'value'=>'$data->validador!=null?$data->validador->nombre:""'),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{view}',
                    'header'=>'Modificaciones',
                    'buttons'=>array(
                        'view'=>array(
                            'label'=>'Ver Historial de modificaciones',
                            'url'=>'Yii::app()->createUrl("//rCamionPropio/verHistorial/$data->id")',
                        ),
                    ),
                ),*/
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_fecha').datepicker();
}
");
?>
