<?php $this->pageTitle=Yii::app()->name; ?>
Bienvenido <?php echo CHtml::encode($nombre);?>, por favor seleccione el informe que desea visualizar:<br/><br/>

<div class="vinculos">
<ul>
	<li><?php echo CHtml::link('Informe administrativo', CController::createUrl("//administrativo/admin",array()))?></li>
	<li><?php echo CHtml::link('Informe de Contratos Adjudicados', CController::createUrl("//contratosAdjudicados/admin",array()))?></li>
	<li><?php echo CHtml::link('Informe Resumen de EP', CController::createUrl("//datos/admin",array()))?></li>
	<li><?php echo CHtml::link('Informe financiero', CController::createUrl("//financiero/admin",array()))?></li>
	<li><?php echo CHtml::link('Informe de Garantías', CController::createUrl("//informeGarantias/admin",array()))?></li>
</ul>
</div>