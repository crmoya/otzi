<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery');

$this->pageTitle = Yii::app()->name;
$noVinculadosV = [];
$criteria = new CDbCriteria;
$criteria->select = 'DISTINCT vehiculo_equipo';
$criteria->condition = "not exists (select * from vehiculo_rindegasto where vehiculo = t.vehiculo_equipo) and vehiculo_equipo != ''";
$vehiculos = GastoCompleta::model()->findAll($criteria);
foreach ($vehiculos as $vehiculo) {
    $noVinculadosV[] = ['vehiculo' => $vehiculo['vehiculo_equipo']];
}

$noVinculadosF = [];
$criteria = new CDbCriteria;
$criteria->select = 'DISTINCT centro_costo_faena';
$criteria->condition = "not exists (select * from faena_rindegasto where faena = t.centro_costo_faena) and centro_costo_faena != ''";
$faenas = GastoCompleta::model()->findAll($criteria);
foreach ($faenas as $faena) {
    $noVinculadosF[] = ['faena' => $faena['faena']];
}

$noVinculadosTC = [];
$criteria = new CDbCriteria;
$criteria->select = 'DISTINCT category_code';
$criteria->condition = "not exists (select * from tipocombustible_rindegasto where tipocombustible = t.category_code) and category_code != '' and expense_policy_id = :fuel_policy";
$params[':fuel_policy'] = GastoCompleta::POLICY_COMBUSTIBLES;
$criteria->params = $params;
$tipos = Gasto::model()->findAll($criteria);
foreach ($tipos as $tipo) {
    $noVinculadosTC[] = ['category_code' => $tipo['category_code']];
}

?>
Bienvenido <?php echo CHtml::encode($nombre); ?>, por favor seleccione una de las siguientes operaciones para comenzar:<br /><br />
<ul>
    <li><?php echo CHtml::link("Administrar usuarios", CController::createUrl('//usuario/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar equipos arrendados", CController::createUrl('//equipoArrendado/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar propietarios de equipos arrendados", CController::createUrl('//propietario/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar equipos propios", CController::createUrl('//equipoPropio/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar operadores", CController::createUrl('//operador/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar camiones, camionetas, autos arrendados", CController::createUrl('//camionArrendado/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar camiones, camionetas, autos propios", CController::createUrl('//camionPropio/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar choferes", CController::createUrl('//chofer/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar destinos", CController::createUrl('//destino/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar orígenes", CController::createUrl('//origen/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar faenas", CController::createUrl('//faena/adminv')); ?></li>
    <li><?php echo CHtml::link("Administrar supervisores de combustible", CController::createUrl('//supervisorCombustible/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar tipos de combustible", CController::createUrl('//tipoCombustible/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar supervisores de rendición de repuestos y combustible", CController::createUrl('//rendidor/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar cuentas contables de repuestos", CController::createUrl('//cuentaContableRepuesto/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar unidades de repuestos", CController::createUrl('//unidad/admin')); ?></li>
    <li><?php echo CHtml::link("Administrar unidades de tiempo", CController::createUrl('//unidadTiempo/admin')); ?></li>
    <li>&nbsp;</li>
    <li><?php echo CHtml::link("Exportar Carpeta Dicc", CController::createUrl('//admin/dicc')); ?></li>
    <!--<li><?php echo CHtml::link("Importar datos a través de Carga Masiva", CController::createUrl('//admin/masiva')); ?></li>-->
    <li>&nbsp;</li>
    <li><?php echo CHtml::link("Modificar o Eliminar registro de camiones, camionetas, autos Propios", CController::createUrl('//rCamionPropio/admin')); ?></li>
    <li><?php echo CHtml::link("Modificar o Eliminar registro de camiones, camionetas, autos Arrendados", CController::createUrl('//rCamionArrendado/admin')); ?></li>
    <li><?php echo CHtml::link("Modificar o Eliminar registro de Equipos Propios", CController::createUrl('//rEquipoPropio/admin')); ?></li>
    <li><?php echo CHtml::link("Modificar o Eliminar registro de Equipos Arrendados", CController::createUrl('//rEquipoArrendado/admin')); ?></li>
    <li>&nbsp;</li>
    <li><?php echo CHtml::link("Asociar vehículos no vinculados de Rinde Gastos (" . count($noVinculadosV) . " sin vincular)", CController::createUrl('//vehiculoRindegastos/vincular')); ?></li>
    <li><?php echo CHtml::link("Asociar faenas no vinculadas de Rinde Gastos (" . count($noVinculadosF) . " sin vincular)", CController::createUrl('//faenaRindegastos/vincular')); ?></li>
    <li><?php echo CHtml::link("Asociar tipos de combustible no vinculadas de Rinde Gastos (" . count($noVinculadosTC) . " sin vincular)", CController::createUrl('//tipoCombustibleRG/vincular')); ?></li>
    <li>&nbsp;</li>
    <li><?php echo CHtml::link("Cambiar mi clave", CController::createUrl('//site/cambiarClave')); ?></li>
    <?php
    /* echo CHtml::ajaxLink(
            'Sincronización data RindeGastos',
            array('/../sincronizadorsam/web/index.php/sincronizador/sincronizar', 'hash' => Tools::generateSecretChipax()),
            array(
                'type' => 'POST',
                "success" => 'js:function(html){
                    $("#sincronizarRindeGastos >.modal-body").html(html);
                    $("#sincronizarRindeGastos").modal("show");
                }'
            ),
            ["id" => "sincronizacion-modal"]
        ); */ ?>
    <!-- <button type="button" id="sincronizacion-modal">Sincronización Data RindeGastos</button> -->
    <button id="sync-combustibles-modal" type="button" class="btn btn-info" data-toggle="modal">
        Obtener datos para Sincronizador <i class="fa fa-sync"></i></button>
    <button id="sync-rindegastos-modal" type="button" class="btn btn-warning" data-toggle="modal">
        Sincronización Generador de Excel <i class="fa fa-sync"></i></button></button>
</ul>

<!-- <div id="myModal" title="Sincronización con RindeGastos">
    <p ok="ok" class="text-center">
        Sincronizando la información desde RindeGastos.
        <br />
        Por favor ESPERE, no cierre esta ventana ni realice ninguna acción hasta que el proceso termine.
    </p>
    <div class="d-flex justify-content-center">
        <div class="spinner-border text-info" style="width: 4rem; height: 4rem;" role="status">
            <span class="sr-only">Sincronizando información desde RindeGastos...</span>
        </div>
    </div>
</div> -->

<div id="myModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sincronización de datos con Chipax y Rinde Gastos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Sincronizando la información desde las APIs.
                <br />
                Por favor ESPERE, no cierre esta ventana ni realice ninguna acción hasta que el proceso termine.
                <br />
                <br />
                <div class="row">
                    <div class="col-md-8 text-center" id="proceso1"></div>
                    <div class="col-md-4" id="estado-proceso-1">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-info" style="width: 1.5rem; height: 1.5rem;" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-center" id="proceso2"></div>
                    <div class="col-md-4" id="estado-proceso-2">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-info" style="width: 1.5rem; height: 1.5rem;" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-center" id="proceso3"></div>
                    <div class="col-md-4" id="estado-proceso-3">
                        <div class="d-flex justify-content-between">
                            <div class="spinner-border text-info" style="width: 1.5rem; height: 1.5rem;" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#sync-rindegastos-modal").on("click", function() {
            $("#proceso1").html("Sincronización con Chipax");
            $("#proceso2").html("Sincronización Rinde Gastos Full");
            $("#proceso3").html("Sincronización de Informes");
            $('#myModal').modal();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->baseUrl; ?>/../sincronizadorsam/web/index.php/sincronizador/sincronizar-chipax-data?hash=<?= Tools::generateSecretChipax() ?>",
            }).then(function(html) {
                $("#estado-proceso-1").html('<i class="fa fa-2x fa-check text-success"></i>');
                return $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->baseUrl; ?>/../sincronizadorsam/web/index.php/sincronizador/sincronizar-rinde-gastos-data?hash=<?= Tools::generateSecretChipax() ?>",
                });
            }).then(function(html) {
                $("#estado-proceso-2").html('<i class="fa fa-2x fa-check text-success"></i>');
                return $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->baseUrl; ?>/../sincronizadorsam/web/index.php/sincronizador/sincronizar-informes-data?hash=<?= Tools::generateSecretChipax() ?>",
                });
            }).then(function(html) {
                $("#estado-proceso-3").html('<i class="fa fa-2x fa-check text-success"></i>');
            }).done(function(html) {
                $(".modal-footer").html('Proceso finalizado! &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-2x fa-check text-success"></i>');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error al ejecutar la solicitud AJAX:", textStatus, errorThrown);
                $("#mensaje-error").text("Error al sincronizar: " + errorThrown);
            });
        });

        $("#sync-combustibles-modal").on("click", function() {
            $("#proceso1").html("Sincronización de Gastos");
            $("#proceso2").html("Sincronización de Informes");
            $("#proceso3").html("Sincronización de Reportes");
            $('#myModal').modal();
            $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->baseUrl; ?>/index.php/tareas/sincronizarGastos?hash=<?= Tools::generateSecretChipax() ?>",
                }).then(function(html) {
                    $("#estado-proceso-1").html('<i class="fa fa-2x fa-check text-success"></i>');
                    return $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->baseUrl; ?>/index.php/tareas/sincronizarInformes?hash=<?= Tools::generateSecretChipax() ?>",
                    });
                }).then(function(html) {
                    $("#estado-proceso-2").html('<i class="fa fa-2x fa-check text-success"></i>');
                    return $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->baseUrl; ?>/index.php/tareas/sincronizarRindeGastos?hash=<?= Tools::generateSecretChipax() ?>",
                    });
                }).then(function(html) {
                    $("#estado-proceso-3").html('<i class="fa fa-2x fa-check text-success"></i>');
                }).done(function(html) {
                    $(".modal-footer").html('Proceso finalizado! &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-2x fa-check text-success"></i>');
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    // Manejar el error si falla alguna de las llamadas
                    console.error("Error al ejecutar la solicitud AJAX:", textStatus, errorThrown);
                    $("#mensaje-error").text("Error al sincronizar: " + errorThrown);
                });
        });
    });
</script>