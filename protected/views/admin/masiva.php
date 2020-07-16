<?php
$form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'masiva-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )
);
?>
<div class="flash-error">
    ATENCIÓN: Por favor respete las siguientes convenciones de nombres de los reports a cargar.<br/><br/>
    <ul>
        <li>REPORT para equipos propios: <b>EquiposPropios.csv</b></li>
        <li>REPORT para equipos arrendados: <b>EquiposArrendados.csv</b></li>
        <li>REPORT para camiones/camionetas/autos propios: <b>CamionesPropios.csv</b></li>
        <li>REPORT para camiones/camionetas/autos arrendados: <b>CamionesArrendados.csv</b></li>
    </ul>
</div>
<div class="flash-notice">
    Seleccione el archivo .CSV que contiene los datos de carga masiva haciendo click sobre "Seleccionar Archivo" y a continuación haga click sobre "Cargar" para comenzar la carga.
</div>
<div class="row">
    <div class="span-4">
        <?=$form->labelEx($model, 'archivo');?>
    </div>
    <div class="span-8">
        <?=$form->fileField($model, 'archivo');?>
    </div>
</div>
<div class="clearfix"></div>
<br/>
<div class="row">
    <div class="span-12">
        <?=$form->error($model, 'archivo');?>
    </div>
</div>
<div class="clearfix"></div>
<br/>
<div class="row">
    <div class="span-2">
        <?=CHtml::submitButton('Cargar');?>
    </div>
</div>
<br/>
<?php
$this->endWidget();
?>