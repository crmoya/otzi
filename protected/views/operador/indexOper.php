<?php $this->pageTitle=Yii::app()->name; ?>
Bienvenido <?php echo CHtml::encode($nombre);?>, por favor seleccione una de las siguientes operaciones para comenzar:<br/><br/>
<div class="menu">
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/crearContrato.jpg');
		echo CHtml::link($imghtml,CController::createUrl('//contratos/create')); 
		echo CHtml::link("Crear un contrato</br>&nbsp;",CController::createUrl('//contratos/create'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/uscon.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminNuevos')); 
		echo CHtml::link("Adjudicar un contrato",CController::createUrl('//contratos/adminNuevos'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/grafico.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminAdjudicados3')); 
		echo CHtml::link("Modificar Flujos Programados",CController::createUrl('//contratos/adminAdjudicados3'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/contrato.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminAdjudicados')); 
		echo CHtml::link("Agregar Resolución",CController::createUrl('//contratos/adminAdjudicados'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/tipo_garantias.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//garantias/adminGarantias')); 
		echo CHtml::link("Agregar Garantías a contrato",CController::createUrl('//garantias/adminGarantias'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/grafico.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminAdjudicados2')); 
		echo CHtml::link("Ingresar Producción Real",CController::createUrl('//contratos/adminAdjudicados2'));
		?>
	</div>
	<br/><br/>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/terminar.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminNoCerrados')); 
		echo CHtml::link("Cerrar un contrato</br>&nbsp;",CController::createUrl('//contratos/adminNoCerrados'));
		?>
	</div>
	<div class="spacer"></div>	
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/informes_logo.png');
		echo CHtml::link($imghtml, CController::createUrl('//site/informes')); 
		echo CHtml::link("Visualizar informes",CController::createUrl('//site/informes'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/adjunto.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminAdjuntar')); 
		echo CHtml::link("Digitalizaciones y Archivos",CController::createUrl('//contratos/adminAdjuntar'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/contrato.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminContratosOp')); 
		echo CHtml::link("Visualizar Contrato",CController::createUrl('//contratos/adminContratosOp'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/clave.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//site/cambiarClave')); 
		echo CHtml::link("Cambiar mi clave<br/>&nbsp;",CController::createUrl('//site/cambiarClave'));
		?>
	</div>
</div>