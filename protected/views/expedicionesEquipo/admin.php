<?php

Yii::app()->getController()->pageTitle="Registros de Expediciones de Equipos";
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
            window.location = "<?=CController::createUrl("//gerencia/adjuntos");?>?ids="+registros+"&tipo=EP";
        }
        else{
            alert('Para exportar los adjuntos, debe seleccionar al menos un registro.');
        }
        e.stopPropagation();
    });
    $(document).on('click','.validate-all',function(e){
        if (confirm('¿Está seguro de que desea validar todos los reports que están siendo filtrados?')) {
            var reports = Array();
            var i = 0;
            $('.validate').each(function() {
                var rep_id = $(this).attr('id_reg');
                reports[i] = rep_id;
                i++;
            });
            var reports_str = reports.join();
            console.log(reports_str);
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('//rEquipoPropio/validar/'); ?>",
                data: {
                    reports: reports_str
                }
            }).done(function(msg) {
                if (msg != 'OK') {
                    alert(msg);
                }
                else{
                    location.reload();
                }
            });
        }
    });
    $(document).on('click','.validate',function(e){
        if (confirm('¿Está seguro de que desea validar este report?')) {
            var rep_id = $(this).attr('id_reg');
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('//rEquipoPropio/validar/'); ?>",
                data: {
                    reports: rep_id
                }
            }).done(function(msg) {
                if (msg != 'OK') {
                    alert(msg);
                }
                else{
                    location.reload();
                }
            });
        }
        e.stopPropagation();
    });
});
</script>
