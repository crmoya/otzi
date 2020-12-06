<?php


Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_fecha').datepicker();
}
");

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('informe-reg-exp-camion-propio-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Registros de Expediciones de Camiones Propios</h1>

<?php echo CHtml::link('Búsqueda Avanzada', '#', array('class' => 'search-button')); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo CHtml::link('Exportar a Excel', 'exportar'); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button id="adjuntos">Exportar Adjuntos</button>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->

<div class="wrapper">

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'informe-reg-exp-camion-propio-grid',
    'dataProvider' => $model->search(),
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'columns' => array(
        array(
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
                true
            ),
        ),
        'reporte',
        'observaciones',
        'observaciones_obra',
        'camion',
        'codigo',
        ['name' => 'kmRecorridos', 'value' => 'number_format($data->kmRecorridos,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'kmGps', 'value' => 'number_format($data->kmGps,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'horas', 'value' => 'number_format($data->horas,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'combustible', 'value' => 'number_format($data->combustible,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'repuesto', 'value' => '"$".number_format($data->repuesto,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'produccionReal', 'value' => '"$".number_format($data->produccionReal,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'horasPanne', 'value' => 'number_format($data->horasPanne,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        'panne',
        array(
            'class' => 'ViewCButtonColumn',
            'header' => 'Ver',
        ),
        array(
            'class' => 'CPCustomButton',
            'template' => '{destacar}',
            'header' => '<img src="' . Yii::app()->baseUrl . '/images/check_old.png" id="check_all">',
            'buttons' => array(
                'destacar' => array(
                    'label' => 'Validar Report',
                    'options' => array('class' => 'check_validar'),
                    'url' => '($data->registro->validado != 2)?$data->id_reg:""',
                ),
            ),
        ),
        array('name' => 'validador_nm', 'value' => '$data->registro->validador!=null?$data->registro->validador->nombre:""', 'filter' => false),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'header' => 'Modif.',
            'buttons' => array(
                'view' => array(
                    'label' => 'Ver Historial de modificaciones',
                    'url' => 'Yii::app()->createUrl("//rCamionPropio/verHistorial/$data->id_reg")',
                ),
            ),
        ),
        
        array(
            'class'=>'CLinkColumn',
            'header'=>'Adjuntos',
            'imageUrl'=>Yii::app()->request->baseUrl .'/images/search.png',
            'urlExpression'=>'array("admin/preview","id"=>$data->id_reg,"tipo"=>"camiones_propios")',
        ),

        array(
            'name' => 'check',
            'id' => 'selectedIds',
            'value' => '$data->id_reg',
            'class' => 'CCheckBoxColumn',
            'checkBoxHtmlOptions' => array('class' => 'adjuntos'),
            'selectableRows' => '100',
            
        ),
    ),
));

?>
</div>
<style>
    #check_all:hover {
        cursor: pointer;
    }
    .wrapper{
        width:110%;
	    overflow-x: auto;
        position: relative;
        left: -50px;
    }
    .span-19{
        width:100%;
    }
</style>
<script>
    $(document).ready(function(e) {
        $(document.body).on('click', '.check_validar', function(e) {
            var report = $(this).attr('href');
            if (!isNaN(report)) {
                if (confirm('¿Está seguro de que desea validar este report?')) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//rCamionPropio/validar/'); ?>",
                        data: {
                            reports: report
                        }
                    }).done(function(msg) {
                        if (msg != 'OK') {
                            alert(msg);
                        }
                        $.fn.yiiGridView.update('informe-reg-exp-camion-propio-grid', {
                            data: $(this).serialize()
                        });
                    });
                }
            }
            return false;
        });
        $(document.body).on('click', '#check_all', function(e) {
            if (confirm('¿Está seguro de que desea validar todos los reports que están siendo filtrados?')) {
                var reports = Array();
                var i = 0;
                $('.check_validar').each(function() {
                    var rep_id = $(this).attr('href');
                    reports[i] = rep_id;
                    i++;
                });
                var reports_str = reports.join();
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('//rCamionPropio/validar/'); ?>",
                    data: {
                        reports: reports_str
                    }
                }).done(function(msg) {
                    if (msg != 'OK') {
                        alert(msg);
                    }
                    $.fn.yiiGridView.update('informe-reg-exp-camion-propio-grid', {
                        data: $(this).serialize()
                    });
                });
            }
        });

        $(document.body).on('click', '#adjuntos', function(e) {
            var registros = "";
            $('.adjuntos').each(function() {
                if($(this).prop('checked')){
                    registros += $(this).val()+"-";
                }
            });
            if(registros != ""){
                registros = registros.substring(0,registros.length-1);
                window.location = "<?=CController::createUrl("//gerencia/adjuntos");?>?ids="+registros+"&tipo=CP";
            }
            else{
                alert('Para exportar los adjuntos, debe seleccionar al menos un registro.');
            }
        });
    });
</script>
<style>
    .check_validar:hover {
        cursor: pointer;
    }
</style>