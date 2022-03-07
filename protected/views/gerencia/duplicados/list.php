<?php

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');

?>
<h5>Eliminación de registros de gastos duplicados</h5>

<div class="row">
    <div class="col-md-12">
        Actualmente hay <?=$duplicados?> registros duplicados <div class='btn btn-danger' id='eliminar'>Eliminar <i class='fa-solid fa-trash-can'></i></div>
        <!--Actualmente hay <?=$duplicados?> registros duplicados <a class='btn btn-success' href="<?=CController::createUrl('gerencia/verDuplicados')?>">Ver <i class='fa-solid fa-list'></i></a>-->
    </div>
</div>

<style>
    .row {
        margin-bottom: 20px;
    }
</style>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function(e){
        $('#eliminar').click(function(){
            Swal.fire({
                title: "Intentando borrar registros duplicados",
                text: "Por favor espere...",
                icon: 'info',
            });
            $.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//gerencia/eliminarDuplicados'); ?>",
			}).done(function(message) {
                Swal.close()
                var respuesta = JSON.parse(message)
                if(respuesta.status == "OK")
                {
                    Swal.fire({
                        title: "Registros duplicados eliminados",
                        text: "Se eliminaron " + respuesta.eliminados + " registros duplicados",
                        icon: 'success',
                    }).then(function(e){
                        location.reload()
                    });
                }
				if(respuesta.status == "ERROR")
                {
                    Swal.fire({
                        title: "ERROR",
                        text: "No se pudieron eliminar los registros duplicados. Error: " + respuesta.message,
                        icon: 'error',
                    }).then(function(){
                        location.reload()
                    });
                }
			});
        })
    })
</script>
