<?php

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/redmond/jquery-ui.css"/>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php

Yii::app()->getController()->pageTitle="Compras duplicadas";

?>

<?php
echo $this->renderPartial('//gerencia/duplicados/_cuerpo',['datos'=>$datos, 'cabeceras' => $cabeceras, 'extra_datos'=>$extra_datos]);
?>
<?php echo $this->renderPartial('//gerencia/duplicados/_footer',['extra_datos'=>$extra_datos]); ?>


<div class="row row-eliminar">
    <div class="col-md-11"></div>
    <div class="col-md-1">
        <div class="btn btn-info eliminar">Eliminar</div>
    </div>
</div>

<script>
    $(document).ready(function(e){
        var allSelected = false;
        $('.select-all').click(function(e){
            var checked = 'checked';
            if(allSelected){
                checked = '';
            }
            allSelected = !allSelected;
            $('.checkbox').prop('checked',checked);
        });

        $('.eliminar').click(function(e){

            var ids = [];

            $('.checkbox').each(function(e){
                if($(this).prop('checked'))
                {
                    ids.push($(this).attr('valor'))
                }
            })

            if(ids.length == 0)
            {
                Swal.fire({
                    title: "ERROR",
                    text: "No ha seleccionado registros para eliminar.",
                    icon: 'error',
                })
                return
            }

            Swal.fire({
                title: "Intentando borrar registros duplicados",
                text: "Por favor espere...",
                icon: 'info',
            })
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('//gerencia/eliminarDuplicados/'); ?>",
                data: {
                    ids: ids,
                    tipo: 'compras',
                }
            }).done(function(msg) {
                Swal.close()
                var respuesta = JSON.parse(msg)
                if (respuesta.status != 'SUCCESS') {
                    Swal.fire({
                        title: "ERROR",
                        text: "No se pudieron eliminar los registros duplicados. Error: " + respuesta.message,
                        icon: 'error',
                    }).then(function(){
                        location.reload()
                    });
                }
                else{
                    Swal.fire({
                        title: "Registros duplicados eliminados",
                        text: "Se eliminaron " + respuesta.message + " registros duplicados",
                        icon: 'success',
                    }).then(function(e){
                        location.reload()
                    });
                }
            })
        })
    })
</script>

<style>
    .row-eliminar
    {
        top: 170px;
        right: 100px;
        position: absolute;
    }
</style>