<?php $this->pageTitle=Yii::app()->name; ?>
Bienvenido <?php echo CHtml::encode($nombre);?>, por favor seleccione una de las siguientes operaciones para comenzar:<br/><br/>
<ul>
 <li><?php echo CHtml::link("Informe de Producción de Maquinaria",CController::createUrl('//produccionMaquinaria/admin')); ?></li>
 <li><?php echo CHtml::link("Informe de Producción de camiones, camionetas, autos",CController::createUrl('//produccionCamiones/admin')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe de Gasto de Combustible",CController::createUrl('//gastoCombustible/admin')); ?></li>
 <li><?php echo CHtml::link("Informe de Gasto de Repuestos",CController::createUrl('//gastoRepuesto/admin')); ?></li>
 <li><?php echo CHtml::link("Informe de Gasto de Remuneraciones",CController::createUrl('//gastoRemuneraciones/admin')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe de Resultados",CController::createUrl('//resultados/admin')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe de Consumo de Maquinaria",CController::createUrl('//consumoMaquinaria/admin')); ?></li>
 <li><?php echo CHtml::link("Informe de Consumo de camiones, camionetas, autos",CController::createUrl('//consumoCamiones/admin')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Informe Cálculo Trato Operador",CController::createUrl('//gerencia/operario')); ?></li>
 <li><?php echo CHtml::link("Informe Cálculo Trato Chofer",CController::createUrl('//gerencia/chofer')); ?></li>
</ul>
<ul>
 <li><?php echo CHtml::link("Reporte Diario de camiones, camionetas, autos Propios",CController::createUrl('//expedicionesCamionPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Reporte Diario de camiones, camionetas, autos Arrendados",CController::createUrl('//expedicionesCamionArrendado/admin')); ?></li>
 <li><?php echo CHtml::link("Reporte Diario de Equipos Propios",CController::createUrl('//expedicionesEquipoPropio/admin')); ?></li>
 <li><?php echo CHtml::link("Reporte Diario de Equipos Arrendados",CController::createUrl('//expedicionesEquipoArrendado/admin')); ?></li>
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
 <li><?php echo CHtml::link("Visualizar gastos de REMUNERACIONES",CController::createUrl('//gastoCompleta/admin',['remuneraciones'=>1])); ?></li>
</ul>

<ul>
 <li><a target="_blank" href="<?php echo Yii::app()->baseUrl;?>/../sincronizadorsam/web/index.php/sincronizador?hash=<?=Tools::generateSecretChipax()?>">Aplicación de sincronización gastos CHIPAX-RINDEGASTOS</a></li>
 <li><a target="_blank" href="<?php echo Yii::app()->baseUrl;?>/../sincronizadorsam/web/index.php/sincronizador/rinde-gastos?hash=<?=Tools::generateSecretChipax()?>">Aplicación de sincronización gastos RINDEGASTOS</a></li>
</ul>

<ul>
 <li><?php echo CHtml::link("Eliminación de gastos duplicados",CController::createUrl('//gerencia/duplicados')); ?></li>
</ul>

<ul>
 <li><?php echo CHtml::link("Cambiar mi clave",CController::createUrl('//site/cambiarClave')); ?></li>
</ul>
