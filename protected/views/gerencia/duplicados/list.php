<?php

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');

?>
<h5>Eliminación de registros de gastos duplicados</h5>

<ul>
    <li>
        Hay <?=$gastos?> gastos duplicados <a class='btn btn-success boton' href="<?=CController::createUrl('gerencia/gastosDuplicados')?>">Ver <i class='fa-solid fa-list'></i></a>
    </li>
    <li>
        Hay <?=$compras?> compras duplicadas <a class='btn btn-success boton' href="<?=CController::createUrl('gerencia/comprasDuplicadas')?>">Ver <i class='fa-solid fa-list'></i></a>
    </li>
    <li>
        Hay <?=$cargas?> cargas de combustible duplicadas <a class='btn btn-success boton' href="<?=CController::createUrl('gerencia/cargasDuplicadas')?>">Ver <i class='fa-solid fa-list'></i></a>
    </li>
    <li>
        Hay <?=$remuneraciones?> remuneraciones duplicadas <a class='btn btn-success boton' href="<?=CController::createUrl('gerencia/remuneracionesDuplicadas')?>">Ver <i class='fa-solid fa-list'></i></a>
    </li>
</ul>

<style>
    .row {
        margin-bottom: 20px;
    }
    .boton {
        margin: 10px;
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
