<?php

Yii::app()->getController()->pageTitle="Registros de Expediciones de Equipos Propios";
?>


<?php echo $this->renderPartial('_header', ['model'=>$model]); ?>

<?php
echo $this->renderPartial('//tables/_cuerpo',['datos'=>$datos, 'cabeceras' => $cabeceras, 'extra_datos'=>$extra_datos]);
?>
<?php echo $this->renderPartial('//tables/_footer',['extra_datos'=>$extra_datos]); ?>

<script>
$(document).ready(function(e){
    $('.exportar-adjuntos').click(function(e) {
        var registros = "";
        $('.check-adjunto').each(function() {
            if($(this).prop('checked')){
                registros += $(this).val()+"-";
            }
        });
        if(registros != ""){
            registros = registros.substring(0,registros.length-1);
            window.location = "<?=CController::createUrl("//gerencia/adjuntos");?>?ids="+registros+"&tipo=CA";
        }
        else{
            alert('Para exportar los adjuntos, debe seleccionar al menos un registro.');
        }
    });
});
</script>
