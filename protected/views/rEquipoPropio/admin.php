<h4>Registros de Expediciones de Equipos Propios</h4>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'requipo-propio-grid',
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
		array('name'=>'equipo', 'value'=>'$data->equipos!=null?$data->equipos->nombre:""'),
		array('name'=>'usuario', 'value'=>'$data->usuarios!=null?$data->usuarios->nombre:""'),
		array(
			'class'=>'MyCButtonColumn',
		),
                /*array(
			'class'=>'EPCustomButton',
			'template'=>'{destacar}',
			'header'=>'¿Validado?',
			'buttons'=>array
			(
					'destacar' => array
					(
                                            'label'=>'Validar Report',
                                            'url'=>'($data->validado != 1)?Yii::app()->createUrl("//rEquipoPropio/validar/$data->id"):""',
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
                            'url'=>'Yii::app()->createUrl("//rEquipoPropio/verHistorial/$data->id")',
                        ),
                    ),
                ),
             * 
             */
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_fecha').datepicker();
}
");
?>
