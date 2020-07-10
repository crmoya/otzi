<?php $this->pageTitle=Yii::app()->name; ?>
Bienvenido <?php echo CHtml::encode($nombre);?>, por favor seleccione una de las siguientes operaciones para comenzar:<br/><br/>

<div class="menu">
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/usuarios.png');
		echo CHtml::link($imghtml, CController::createUrl('//usuarios/admin')); 
		echo CHtml::link("Administrar Usuarios",CController::createUrl('//usuarios/admin'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/uscon.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//admin/usuariosContratos')); 
		echo CHtml::link("Asociar Usuarios a Contratos",CController::createUrl('//admin/usuariosContratos'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/contrato.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/admin')); 
		echo CHtml::link("Administrar Contratos",CController::createUrl('//contratos/admin'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/res.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminConRes')); 
		echo CHtml::link("Modificar última resolución",CController::createUrl('//contratos/adminConRes'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/instituciones.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//instituciones/admin')); 
		echo CHtml::link("Administrar Instituciones",CController::createUrl('//instituciones/admin'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/tipo_garantias.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//tiposGarantias/admin')); 
		echo CHtml::link("Administrar Tipos de Garantía",CController::createUrl('//tiposGarantias/admin'));
		?>
	</div>
	<div class="spacer"></div>
	<br/></br>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/tipo_garantias.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//objetosGarantias/admin')); 
		echo CHtml::link("Administrar Objetos Garantía",CController::createUrl('//objetosGarantias/admin'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/adjunto.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminDig')); 
		echo CHtml::link("Eliminar<br/>Adjuntos",CController::createUrl('//contratos/adminDig'));
		?>
	</div>
	<div class="spacer"></div>
	<div class="boton">
		<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/terminar.jpg');
		echo CHtml::link($imghtml, CController::createUrl('//contratos/adminCerrados')); 
		echo CHtml::link("Reabrir un contrato&nbsp;",CController::createUrl('//contratos/adminCerrados'));
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