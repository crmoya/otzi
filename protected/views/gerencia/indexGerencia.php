<?php $this->pageTitle=Yii::app()->name; ?>
Bienvenido <?php echo CHtml::encode($nombre);?>, por favor seleccione una de las siguientes operaciones para comenzar:<br/><br/>
<ul>
 <li><?php echo CHtml::link("Informe de Producción de Maquinaria",CController::createUrl('//gerencia/produccionMaquinaria')); ?></li>
 <li><?php echo CHtml::link("Informe de Producción de camiones, camionetas, autos",CController::createUrl('//gerencia/produccionCamiones')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe de Gasto de Combustible",CController::createUrl('//gastoCombustible/admin')); ?></li>
 <li><?php echo CHtml::link("Informe de Gasto de Repuestos",CController::createUrl('//gastoRepuesto/admin')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe de Resultados",CController::createUrl('//gerencia/resultados')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe de Consumo de Maquinaria",CController::createUrl('//gerencia/consumoMaquinaria')); ?></li>
 <li><?php echo CHtml::link("Informe de Consumo de camiones, camionetas, autos",CController::createUrl('//gerencia/consumoCamiones')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe Cálculo Trato Operador",CController::createUrl('//gerencia/operario')); ?></li>
 <li><?php echo CHtml::link("Informe Cálculo Trato Chofer",CController::createUrl('//gerencia/chofer')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Reporte Diario de camiones, camionetas, autos Propios",CController::createUrl('//informeRegExpCamionPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Reporte Diario de camiones, camionetas, autos Arrendados",CController::createUrl('//informeRegExpCamionArrendado/admin')); ?></li>
 <li><?php echo CHtml::link("Reporte Diario de Equipos Propios",CController::createUrl('//informeRegExpEquipoPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Reporte Diario de Equipos Arrendados",CController::createUrl('//informeRegExpEquipoArrendado/admin')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Ver Expediciones de camiones, camionetas, autos Propios",CController::createUrl('//expediciones/admin')); ?></li>
 <li><?php echo CHtml::link("Ver Observaciones de Reportes Diarios",CController::createUrl('//observaciones/admin')); ?></li>
 <li><?php echo CHtml::link("Ver Cargas de Combustible",CController::createUrl('//informecombustible/admin')); ?></li>
 <li><?php echo CHtml::link("Ver Compras de Repuesto",CController::createUrl('//informerepuestos/admin')); ?></li>
</ul>

<ul>
 <li><?php echo CHtml::link("Visualizar gastos RindeGastos COMBUSTIBLES",CController::createUrl('//gastoCompleta/admin',['policy'=>GastoCompleta::POLICY_COMBUSTIBLES])); ?></li>
 <li><?php echo CHtml::link("Visualizar gastos RindeGastos DEPARTAMENTO DE MAQUINARIA DIFERENTE DE COMBUSTIBLES",CController::createUrl('//gastoCompleta/admin',['policy'=>41786])); ?></li>
</ul>

<ul>
 <li><?php echo CHtml::link("Cambiar mi clave",CController::createUrl('//site/cambiarClave')); ?></li>
</ul>