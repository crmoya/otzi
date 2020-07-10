<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.currency.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 

?>
<script language="javascript" type="text/javascript">
	$(function() {

		
		$("#flujos").hide();
		$(".fecha_final").change(function(){
			var valid = true;
			valid = valid && checkFechaInicio();
			valid = valid && checkFechas(2);
			valid = valid && checkCompareFechas();
			if(valid){
				$("#flujos").fadeIn();
			}
			else{
				$("#flujos").fadeOut();
			}
		});
		
		$("#guardar").click(function(){
			var valid = true;
			return valid;
		});
		
		
			
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Digitalizaciones de Garantías de contrato <?php echo CHtml::encode($contrato->nombre);?></h3>

<?php if(Yii::app()->user->hasFlash('adjuntarMessage')): ?>

<div class="flash-success">
<?php echo Yii::app()->user->getFlash('adjuntarMessage'); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('adjuntarError')): ?>

<div class="flash-error">
<?php echo Yii::app()->user->getFlash('adjuntarError'); ?>
</div>
<?php endif; ?>

<?php if(!Yii::app()->user->hasFlash('adjuntarMessage') && !Yii::app()->user->hasFlash('adjuntarError')): ?>

<div class="form" style="width:900px;">

	<table>
                <tr>
                        <td><b>RUT Mandante:</b></td>
                        <td><?php echo CHtml::encode(Tools::backFecha($contrato->nombre_mandante));?></td>
                        <td><b>RUT Mandante:</b> <?php echo CHtml::encode(Tools::backFecha($contrato->rut_mandante));?></td>
                        <td><?php echo CHtml::encode(Tools::backFecha($contrato->rut_mandante));?></td>
                </tr>
		
		<tr>
			<td><b>Fecha Inicio:</b> <?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
			<td><b>Fecha Fin:</b> <?php if($ultima_res!=null) echo CHtml::encode(Tools::backFecha($ultima_res->fecha_final));?></td>
			<td><b>Estado:</b> <?php echo CHtml::encode(EstadosContratos::model()->findByPk($contrato->estados_contratos_id)->nombre);?></td>
			<td><b>Plazo:</b> <?php echo CHtml::encode($contrato->plazo);?> Días</td>
		</tr>
		<tr>
			<td><b>Monto Inicial:</b> <span class="plata"><?php echo CHtml::encode($contrato->monto_inicial);?></span></td>
			<td><b>Modificaciones Monto:</b> <span class="plata"><?php echo CHtml::encode($contrato->modificaciones_monto);?></span></td>
			<td><b>Monto Actualizado:</b> <span class="plata"><?php echo CHtml::encode($contrato->monto_actualizado);?></span></td>
			<td></td>
		</tr>
		<tr>
			<td><b>Creador:</b> <?php echo CHtml::encode(Usuarios::model()->findByPk($contrato->creador_id)->nombre);?></td>
			<td><b>Última Modificación:</b> <?php echo CHtml::encode(Usuarios::model()->findByPk($contrato->modificador->id)->nombre);?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr><td colspan="4"><b>Observación:</b> <?php echo CHtml::encode($contrato->observacion);?></td></tr>
	</table>
	
	<fieldset>
		<legend>Garantías del Contrato</legend>
		<?php 
		$iterator=0;
		foreach($garantias as $i=>$iter):?>
		<table class="tableResolucion">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="3">
								<div>
									<b>Generada por:</b>
									<?php 
									$usuario = Usuarios::model()->findByPk($iter->creador_id);
									echo CHtml::encode($usuario->nombre); 
									?>
									&nbsp;&nbsp;
									<b>Modificada por:</b>
									<?php 
									$usuario = Usuarios::model()->findByPk($iter->modificador_id);
									echo CHtml::encode($usuario->nombre); 
									?>
							</td>
						</tr>
						<tr>
							<td>
									<b>N°Garantía:</b>
									<?php echo CHtml::encode($iter->numero);?>
							</td>
							<td>
									<b>Monto:</b>
									<?php echo CHtml::encode($iter->tipo_monto);?>
									<span class="plata_decimales_sin"><?php echo CHtml::encode($iter->monto);?></span> 
							</td>
							<td>
									<b>Fecha Vencimiento:</b>
									<?php echo CHtml::encode($iter->fecha_vencimiento);?>
							</td>
						</tr>
						<tr>
							<td>
								<b>Institución asociada: </b>
								<?php 
								$inst_nom = Instituciones::model()->findByPk($iter->instituciones_id)->nombre;
								echo CHtml::encode($inst_nom);?>
							</td>
							<td>		
								<b>Tipo de garantía: </b>
								<?php 
								$tipo_nom = TiposGarantias::model()->findByPk($iter->tipos_garantias_id)->nombre;
								echo CHtml::encode($tipo_nom);?>
							</td>
							<td>	
								<b>Objeto de garantía: </b>
								<?php 
								$obj_nom = ObjetosGarantias::model()->findByPk($iter->objetos_garantias_id)->descripcion;
								echo CHtml::encode($obj_nom);?>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<b>Observación:</b>
								<?php echo CHtml::encode($iter->observacion);?>
							</td>
						</tr>
					</table>
					
					<fieldset>
						<legend>Digitalizaciones de la Garantía:</legend>
						<?php $form=$this->beginWidget('CActiveForm', array(
							'id'=>'editar-form',
							'enableClientValidation'=>true,
							'htmlOptions' => array('enctype' => 'multipart/form-data'),
							'clientOptions'=>array(
								'validateOnSubmit'=>true,
						),
						)); ?>
						<div>Nueva Digitalización
							<?php
							$archivo = new AdjuntosGarantias();
							echo $form->fileField($archivo, 'file');
							echo $form->hiddenField($archivo,'garantias_id',array('value'=>$iter->id));
							echo CHtml::submitButton('Guardar'); ?>
						</div>
						<table class="digitalizaciones">
						<?php 
							$adjuntos = $iter->getAdjuntos();
							$i=1;
							foreach($adjuntos as $adjunto):
						?>
							<tr>
								<td><?php echo $i.". ".CHtml::encode($adjunto->nombre_archivo);?></td>
								<td style="font-size:0.8em;">adjunta el 
									<?php echo Tools::backFecha($adjunto->fecha);?> por
									<?php $usuario = Usuarios::model()->findByPk($adjunto->subidor_id)->nombre;
									echo CHtml::encode($usuario);?>
								</td>
								<td><?php echo CHtml::link("descargar",CController::createUrl('//site/download',array('path'=>$iter->id.$adjunto->nombre_archivo,'nombre'=>$adjunto->nombre_archivo,'tipo'=>'garantia')));?></td>
							</tr>
						<?php 
							$i++;
							endforeach;
						?>
						</table>
						
						<?php $this->endWidget();?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php 
		endforeach;?>
	</fieldset>
	
		
	
	
	<?php endif;?>

</div>
<!-- form -->


