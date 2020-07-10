<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/epTemplate.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.currency.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 

$prodReales = 0;

?>
<script language="javascript" type="text/javascript">
	$(function() {
		$("#guardar").click(function(){
			var valid = true;
			//valid = valid && checkProduccionesReales();
			return valid;
		});
		if(produccionesReales == 0){
			$("#guardar").attr("disabled","true");
		}

		calculaSumaProd();
		calculaSumaCosto();
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Ingresar Flujos Reales a <?php echo CHtml::encode($contrato->nombre);?></h3>

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
	'id'=>'flujos-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>
	<table>
                <tr>
                        <td><?php echo $form->labelEx($contrato,'rut_mandante'); ?></td><td><?php echo CHtml::encode($contrato->rut_mandante);?></td>
                        <td><?php echo $form->labelEx($contrato,'nombre_mandante'); ?></td><td><?php echo CHtml::encode($contrato->nombre_mandante);?></td>
                </tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'fecha_inicio');?></td>
			<td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
			<td style="font-size:0.9em;"><b>Fecha Fin del Contrato</b></td>
			<td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($ultima_res->fecha_final));?></td>
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
		</tr>
		<?php endif;?>
		<tr>
			<td><?php echo $form->labelEx($contrato,'codigo_safi');?></td>
			<td><?php echo CHtml::encode($contrato->codigo_safi);?></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<fieldset>
		<legend>Resoluciones del Contrato</legend>
		<?php 
		$r=0;
		$l = 0;
		$i = 0;
		$primera = true;
		foreach($resoluciones as $res):?>
		<table class="tableResolucion">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table>
									<tr>
										<td><?php echo $form->labelEx($res,"numero");?></td>
										<td><?php echo CHtml::encode($res->numero); ?></td>
										<td><?php echo $form->labelEx($res,"monto");?></td>
										<td class="plata"><?php echo CHtml::encode($res->monto); ?></td>
										<td style="font-size:0.9em;"><b>Generada por:</b></td>
										<td><?php 
											$usuario = Usuarios::model()->findByPk($res->creador_id);
											echo CHtml::encode($usuario->nombre); 
											?>
										</td>
										<td style="font-size:0.9em;"><b>Modificada por:</b></td>
										<td><?php 
											$usuario = Usuarios::model()->findByPk($res->modificador_id);
											echo CHtml::encode($usuario->nombre); 
											?>
										</td>
									</tr>
									<tr>
										<td><?php echo $form->labelEx($res,"fecha_resolucion");?></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_resolucion)); ?></td>
										<?php if($primera):
										$primera=false;?>
										<td><b class="label">Fecha Inicio Obra:</b></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_inicio)); ?></td>
										<?php else:?>
										<td><?php echo $form->labelEx($res,"fecha_inicio");?></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_tramitada)); ?></td>
										<?php endif;?>
										<td><?php echo $form->labelEx($res,"fecha_final");?></td>
										<td>
											<?php echo CHtml::encode(Tools::backFecha($res->fecha_final)); ?>
											<input type="hidden" value="<?php echo CHtml::encode(Tools::backFecha($res->fecha_final)); ?>" class="fecha_min"/>
										</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php echo $form->labelEx($res,"observacion");?></td>
										<td colspan="7"><?php echo CHtml::encode($res->observacion); ?></td>
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
								<fieldset class="flujoReal2">
									<legend>Flujo <?php echo CHtml::encode(Tools::getMes($flujoP->mes)); ?> <?php echo CHtml::encode($flujoP->agno); ?></legend>
									<table>
										<tr>
											<td><?php echo $form->labelEx($flujoP,"produccion");?><?php echo $form->hiddenField($flujoP,"produccion",array('id'=>"produccion$l",'class'=>'produccion'));?></td>
											<td class="plata">
												<?php echo CHtml::encode($flujoP->produccion); ?>
											</td>
											<td><?php echo $form->labelEx($flujoP,"costo");?><?php echo $form->hiddenField($flujoP,"costo",array('id'=>"costo$l",'class'=>'costo'));?></td>
											<td class="plata"><?php echo CHtml::encode($flujoP->costo); ?></td>
											<td width="250"></td>
										</tr>
										<tr>
											<td><?php echo $form->labelEx($flujoP,"comentarios");?></td>
											<td colspan="2"><?php echo CHtml::encode($flujoP->comentarios); ?></td>
										</tr>
									</table>
									
									<table style="font-size:0.9em;">
										<tr>
											<td><b>Producción Programada<br/>Acumulada Neta</b></td>
											<td id="prodAcum<?php echo $l;?>"></td>
											<td><b>Costo Programado<br/>Acumulado Neto</b></td>
											<td id="costoAcum<?php echo $l;?>"></td>
											<td></td>
										</tr>
									</table>
									<?php $l++;?>
									<?php 
									
									//si el flujo real ya se agregó entonces no se puede editar									
									if($eps != null):
									?>
									<fieldset>
									<legend><small>EP's <?php echo CHtml::encode(Tools::getMes($flujoP->mes)); ?> <?php echo CHtml::encode($flujoP->agno); ?></small></legend>
									<?php 
									//debo sacar los flujos correspondientes a este mes, año y resolucion
									$flujos = Tools::getEP($eps,$flujoP->mes,$flujoP->agno,$flujoP->resoluciones_id);
									
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
									<?php 
									endif; // $eps != null
									
									
									?>
									<?php 
									//por otro lado, si el flujo real no se ha agregado, se puede agregar
									if($eps == null):// && Tools::esAntesODurante($flujoP->mes, $flujoP->agno)):											
										
									?>
										<script language="javascript" type="text/javascript">
										produccionesReales++;
										</script>
		
										<fieldset class="eps">
											<legend>EP</legend>
											Agregar un EP: 
											<?php echo CHtml::dropDownList('tipo','',
													array(	'obra' => 'EP de Obra', 
															'anticipo' => 'EP de anticipo', 
															'canje' => 'EP de canje de retenciones'),
															array('class'=>'tipoEP'));?>
											<div class="btn addEP"  resoluciones_id="<?php echo CHtml::encode($flujoP->resoluciones_id); ?>" mes="<?php echo CHtml::encode($flujoP->mes); ?>"  agno="<?php echo CHtml::encode($flujoP->agno); ?>">Agregar</div>
											<div class="agregados">
											</div>											
										</fieldset>
								
									<?php 
										$prodReales++;
										$i++;
									endif;
									?>
									
								</fieldset>									
							</td>
						</tr>
						<?php endforeach;
						endif;?>
					</table>
				</td>
			</tr>
		</table>
		<?php 
		$r++;
		endforeach;?>
	</fieldset>
			
	<!-- cantidad de eps de obra que tengo en la página -->
	<input type="hidden" id="eps_obra" name="eps_obra" value="0"/>
	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


