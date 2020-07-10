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
			valid = valid && checkFechaInicio();
			valid = valid && checkProducciones();
			valid = valid && checkFechas(2);
			valid = valid && checkCompareFechas();
			return valid;
		});
		
		
			
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Contrato <?php echo CHtml::encode($contrato->nombre);?></h3>
<div style="text-align:right;">
	<?php echo CHtml::link('Exportar a Excel','exportar/'.$contrato->id); ?>
</div>
<?php if(Yii::app()->user->hasFlash('resolucionesMessage')): ?>

<div class="flash-success">
<?php echo Yii::app()->user->getFlash('resolucionesMessage'); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('resolucionesError')): ?>

<div class="flash-error">
<?php echo Yii::app()->user->getFlash('resolucionesError'); ?>
</div>
<?php endif; ?>

<?php if(!Yii::app()->user->hasFlash('resolucionesMessage') && !Yii::app()->user->hasFlash('resolucionesError')): ?>

<div class="form" style="width:900px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'editar-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>
	<table>
                <tr>
			<td><?php echo $form->labelEx($contrato,'rut_mandante'); ?></td>
			<td><?php echo CHtml::encode($contrato->rut_mandante);?></td>
			<td><?php echo $form->labelEx($contrato,'nombre_mandante'); ?></td>
			<td><?php echo CHtml::encode($contrato->nombre_mandante);?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'fecha_inicio'); ?></td>
			<td><?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
			<td><?php if($ultima_res!=null) echo $form->labelEx($ultima_res,'fecha_final'); ?></td>
			<td><?php if($ultima_res!=null) echo CHtml::encode(Tools::backFecha($ultima_res->fecha_final));?></td>
			<td><?php echo $form->labelEx($contrato,'estados_contratos_id'); ?></td>
			<td><?php echo CHtml::encode(EstadosContratos::model()->findByPk($contrato->estados_contratos_id)->nombre);?></td>
			<td><?php echo $form->labelEx($contrato,'plazo'); ?></td>
			<td><?php echo CHtml::encode($contrato->plazo);?> Días</td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'monto_inicial'); ?></td>
			<td><span class="plata"><?php echo CHtml::encode($contrato->monto_inicial);?></span></td>
			<td><?php echo $form->labelEx($contrato,'modificaciones_monto'); ?></td>
			<td><span class="plata"><?php echo CHtml::encode($contrato->modificaciones_monto);?></span></td>
			<td><?php echo $form->labelEx($contrato,'monto_actualizado'); ?></td>
			<td><span class="plata"><?php echo CHtml::encode($contrato->monto_actualizado);?></span></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'presupuesto_oficial');?></td>
			<td class="plata"><?php echo CHtml::encode($contrato->presupuesto_oficial);?></td>
			<td><?php echo $form->labelEx($contrato,'codigo_safi');?></td>
			<td><?php echo CHtml::encode($contrato->codigo_safi);?></td>
			<td><?php echo $form->labelEx($contrato,'creador_id');?></td>
			<td><?php echo CHtml::encode(Usuarios::model()->findByPk($contrato->creador_id)->nombre);?></td>
			<td><?php echo $form->labelEx($contrato,'modificador_id');?></td>
			<td><?php echo CHtml::encode(Usuarios::model()->findByPk($contrato->modificador->id)->nombre);?></td>
		</tr>
		<?php 
			$tipoContrato = TiposContratos::model()->findByPk($contrato->tipos_contratos_id);
			$tipoReajuste = TiposReajustes::model()->findByPk($contrato->tipos_reajustes_id);
			if($tipoContrato != null && $tipoReajuste != null):
		?>
		<tr>
			<td><?php echo $form->labelEx($contrato,'tipos_contratos_id'); ?></td>
			<td><?php echo CHtml::encode($tipoContrato->nombre);?></td>
			<td><?php echo $form->labelEx($contrato,'tipos_reajustes_id'); ?></td>
			<td><?php echo CHtml::encode($tipoReajuste->nombre);?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php endif;?>
		<tr>
			<td><?php echo $form->labelEx($contrato,'observacion'); ?></td>
			<td colspan="7"><?php echo CHtml::encode($contrato->observacion);?></td>
		</tr>
	</table>
	
	<fieldset>
		<legend>Resoluciones del Contrato</legend>
		<?php 
		$r=0;
		$primera = true;
		foreach($resoluciones as $res):?>
		<table>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" style="font-size:0.8em;">
									<tr>
										<td>
												<b>N°Resolución:</b>
												<?php echo CHtml::encode($res->numero);?>
										</td>
										<td>
												<b>Valor de Resolución u OC c/IVA:</b>
												<span class="plata"><?php echo CHtml::encode($res->monto);?></span>
										</td>
										<td>
												<b>Plazo:</b>
												<?php echo CHtml::encode($res->plazo); ?>
										</td>
										<td>
												<b>Fecha Resolución:</b>
												<?php echo CHtml::encode(Tools::backFecha($res->fecha_resolucion)); ?>
										</td>
										<td>
											<?php if($primera):
												$primera = false;?>
												<b>Fecha Inicio:</b>
												<?php echo CHtml::encode(Tools::backFecha($res->fecha_inicio)); ?>
											<?php else:?>
												<b>Fecha Tramitada:</b>
												<?php echo CHtml::encode(Tools::backFecha($res->fecha_tramitada)); ?>
											<?php endif;?>
										</td>
										<td>
												<b>Fecha Fin:</b>
												<?php echo CHtml::encode(Tools::backFecha($res->fecha_final)); ?>
										</td>
									</tr>
									<tr>
										<td colspan="6">
											<div>
												<b>Generada por:</b>
												<?php 
												$usuario = Usuarios::model()->findByPk($res->creador_id);
												echo CHtml::encode($usuario->nombre); 
												?>
												&nbsp;&nbsp;
												<b>Modificada por:</b>
												<?php 
												$usuario = Usuarios::model()->findByPk($res->modificador_id);
												echo CHtml::encode($usuario->nombre); 
												?>
										</td>
									</tr>
									<tr>
										<td colspan="6">
											<b>Observación:</b>
											<?php echo CHtml::encode($res->observacion);?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<?php
							if(isset($flujosProgramados[$res->id])):
							foreach($flujosProgramados[$res->id] as $flujoP):
								$eps = $flujoP->getEPs();
								
						?>
						<tr>
							<td>
								<fieldset class="flujoReal3">
									<legend><?php echo CHtml::encode(Tools::getMes($flujoP->mes)); ?> <?php echo CHtml::encode($flujoP->agno); ?></legend>
									<table>
										<tr>
											<td><?php echo $form->labelEx($flujoP,"produccion");?></td>
											<td class="plata"><?php echo CHtml::encode($flujoP->produccion); ?></td>
											<td><?php echo $form->labelEx($flujoP,"costo");?></td>
											<td class="plata"><?php echo CHtml::encode($flujoP->costo); ?></td>
											<td width="300"></td>
										</tr>
										<tr>
											<td><?php echo $form->labelEx($flujoP,"comentarios");?></td>
											<td colspan="4"><?php echo CHtml::encode($flujoP->comentarios); ?></td>
										</tr>
									</table>
									<?php 
									$flujos = array();
									if($eps != null){
										$flujos = Tools::getEP($eps,$flujoP->mes,$flujoP->agno,$flujoP->resoluciones_id);	
									}
									
									foreach($flujos as $ft){
										if($ft['tipo'] == "anticipo"):
										?>
										<div class="rodeado_sin">
											<div style="display:table-cell;width:100px;"><strong>EP Anticipo: </strong></div>
											<div style="display:table-cell;">
												<span>Valor:</span>&nbsp;<span class="plata"><?php echo $ft['ep']->valor;?></span>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<span>Comentarios:</span>&nbsp;<span><?php echo $ft['ep']->comentarios;?></span>
											</div>
										</div>										
										<?php 
										endif; //anticipo
										if($ft['tipo'] == "canje_retencion"):
										?>
										<div class="rodeado_sin">
											<div style="display:table-cell;width:100px;"><strong>EP Canje Retenciones: </strong></div>
											<div style="display:table-cell;">
												<span>Valor:</span>&nbsp;<span class="plata"><?php echo $ft['ep']->valor;?></span>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<span>Comentarios:</span>&nbsp;<span><?php echo $ft['ep']->comentarios;?></span>
											</div>
										</div>
										<?php 	
										endif; // canje_retencion
										if($ft['tipo'] == "obra"):
										?>
										<div class="rodeado_sin">
											<div style="display:table-cell;width:100px;"><strong>EP Obra: </strong></div>
											<div style="display:table-cell;">
												<span>Producción:</span>&nbsp;<span class="plata"><?php echo $ft['ep']->produccion;?></span>
												&nbsp;
												<span>Costo:</span>&nbsp;<span class="plata"><?php echo $ft['ep']->costo;?></span>
												&nbsp;
												<span>Reajuste:</span>&nbsp;<span class="plata"><?php echo $ft['ep']->reajuste;?></span>
												&nbsp;
												<span>Retención:</span>&nbsp;<span class="plata"><?php echo $ft['ep']->retencion;?></span>
												&nbsp;
												<span>Descuento:</span>&nbsp;<span class="plata"><?php echo $ft['ep']->descuento;?></span>
												<br/>
												<span>Comentarios:</span>&nbsp;<span><?php echo $ft['ep']->comentarios;?></span>
											</div>											
										</div>
										<?php 
										endif; // obra
									}//foreach flujos	
									?>	
								</fieldset>									
							</td>
						</tr>
						<?php 
						endforeach;
						endif;
						?>
					</table>
				</td>
			</tr>
		</table>
		<?php 
		$r++;
		endforeach;?>
	</fieldset>
	
	<fieldset>
		<legend>Garantías del Contrato</legend>
		<?php 
		$iterator=0;
		foreach($garantias as $i=>$iter):?>
		<table class="tableResolucion" style="font-size:0.8em;">
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
									<?php echo "<b>".CHtml::encode($iter->tipo_monto)."</b>&nbsp;"; ?>
									<span class="plata_decimales_sin"><?php echo CHtml::encode($iter->monto);?></span> 
							</td>
							<td>
									<b>Fecha Vencimiento:</b>
									<?php echo CHtml::encode(Tools::backFecha($iter->fecha_vencimiento));?>
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
							<td>
								<b>Estado:</b>
								<?php echo CHtml::encode(Tools::estadoGarantia($iter->estado_garantia));?>
							</td>
							<td colspan="2">
							<?php if(!$iter->estado_garantia):?>
								<b>Fecha Devolución:</b>
								<?php echo Tools::backFecha($iter->fecha_devolucion);?>
							<?php endif;?>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<b>Observación:</b>
								<?php echo CHtml::encode(Tools::ponePorcentaje($iter->observacion));?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php 
		echo $form->hiddenField($iter,"[$i]id");
		endforeach;?>
	</fieldset>
		
	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


