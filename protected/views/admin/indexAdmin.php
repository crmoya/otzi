<?php $this->pageTitle=Yii::app()->name; ?>
Bienvenido <?php echo CHtml::encode($nombre);?>, por favor seleccione una de las siguientes operaciones para comenzar:<br/><br/>
<ul>
 <li><?php echo CHtml::link("Administrar usuarios",CController::createUrl('//usuario/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar equipos arrendados",CController::createUrl('//equipoArrendado/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar propietarios de equipos arrendados",CController::createUrl('//propietario/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar equipos propios",CController::createUrl('//equipoPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar operadores",CController::createUrl('//operador/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar camiones, camionetas, autos arrendados",CController::createUrl('//camionArrendado/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar camiones, camionetas, autos propios",CController::createUrl('//camionPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar choferes",CController::createUrl('//chofer/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar destinos",CController::createUrl('//destino/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar orígenes",CController::createUrl('//origen/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar faenas",CController::createUrl('//faena/adminv')); ?></li>
 <li><?php echo CHtml::link("Administrar supervisores de combustible",CController::createUrl('//supervisorCombustible/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar tipos de combustible",CController::createUrl('//tipoCombustible/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar supervisores de rendición de repuestos y combustible",CController::createUrl('//rendidor/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar cuentas contables de repuestos",CController::createUrl('//cuentaContableRepuesto/admin')); ?></li>
 <li><?php echo CHtml::link("Administrar unidades de repuestos",CController::createUrl('//unidad/admin')); ?></li>
 <li>&nbsp;</li>
 <li><?php echo CHtml::link("Exportar Carpeta Dicc",CController::createUrl('//admin/dicc')); ?></li>
 <!--<li><?php echo CHtml::link("Importar datos a través de Carga Masiva",CController::createUrl('//admin/masiva')); ?></li>-->
 <li>&nbsp;</li>
 <li><?php echo CHtml::link("Modificar o Eliminar registro de camiones, camionetas, autos Propios",CController::createUrl('//rCamionPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Modificar o Eliminar registro de camiones, camionetas, autos Arrendados",CController::createUrl('//rCamionArrendado/admin')); ?></li>
 <li><?php echo CHtml::link("Modificar o Eliminar registro de Equipos Propios",CController::createUrl('//rEquipoPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Modificar o Eliminar registro de Equipos Arrendados",CController::createUrl('//rEquipoArrendado/admin')); ?></li>
 <li>&nbsp;</li>
 <li><?php echo CHtml::link("Asociar registros no vinculados de Rinde Gastos",CController::createUrl('//vehiculoRindegastos/vincular')); ?></li>
 <li>&nbsp;</li>
 <li><?php echo CHtml::link("Cambiar mi clave",CController::createUrl('//site/cambiarClave')); ?></li>

</ul>