<?php $this->pageTitle=Yii::app()->name; ?>
Bienvenido <?php echo CHtml::encode($nombre);?>, por favor seleccione una de las siguientes operaciones para comenzar:<br/><br/>
<ul>
 <li><?php echo CHtml::link("Registro Control de Maquinarias para camiones, camionetas, autos Propios",CController::createUrl('//operativo/camionesPropios')); ?></li>
 <li><?php echo CHtml::link("Registro Control de Maquinarias para camiones, camionetas, autos Arrendados",CController::createUrl('//operativo/camionesArrendados')); ?></li>
 <li><?php echo CHtml::link("Registro Control de Maquinarias para Equipos Propios",CController::createUrl('//operativo/equiposPropios')); ?></li>
 <li><?php echo CHtml::link("Registro Control de Maquinarias para Equipos Arrendados",CController::createUrl('//operativo/equiposArrendados')); ?></li>
 <li></li>
 <li><?php echo CHtml::link("Modificar Registro Control de Maquinarias para camiones, camionetas, autos Propios",CController::createUrl('//rCamionPropio/admin2')); ?></li>
 <li><?php echo CHtml::link("Modificar Registro Control de Maquinarias para camiones, camionetas, autos Arrendados",CController::createUrl('//rCamionArrendado/admin2')); ?></li>
 <li><?php echo CHtml::link("Modificar Registro Control de Maquinarias para Equipos Propios",CController::createUrl('//rEquipoPropio/admin2')); ?></li>
 <li><?php echo CHtml::link("Modificar Registro Control de Maquinarias para Equipos Arrendados",CController::createUrl('//rEquipoArrendado/admin2')); ?></li>
 <li></li>
 <li><?php echo CHtml::link("Ingresar camiones, camionetas, autos Propios",CController::createUrl('//camionPropio/create')); ?></li>
 <li><?php echo CHtml::link("Ingresar camiones, camionetas, autos Arrendados",CController::createUrl('//camionArrendado/create')); ?></li>
 <li><?php echo CHtml::link("Ingresar Equipos Propios",CController::createUrl('//equipoPropio/create')); ?></li>
 <li><?php echo CHtml::link("Ingresar Equipos Arrendados",CController::createUrl('//equipoArrendado/create')); ?></li>
 
 <li><?php echo CHtml::link("Cambiar mi clave",CController::createUrl('//site/cambiarClave')); ?></li>
 
</ul>