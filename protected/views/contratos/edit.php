<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/adminTemplate.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.currency.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 


?>
<script language="javascript" type="text/javascript">

	var str_anticipo = "";
	var str_retencion = "";

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
			//valid = valid && checkFechaInicio();
			valid = valid && checkProducciones();
			valid = $("#error_contrato").html() == "";
			//valid = valid && checkFechas(2);
			//valid = valid && checkCompareFechas();
			return valid;
		});

		$('.dinero').each(function(index) {
			var text = $(this).val();
			text = replaceAll(text,',','.');
			text = replaceAll(text,'.','');
			if(!isNaN(text)){
				var num = new Number(text);
				num = num.toFixed(0);
		   		$(this).val(num);
		   		$(this).val(formatThousands($(this).val(),'.'));
			}else{
				$(this).val(0);
			}
			
		});
		
			
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Editar Contrato <?php echo CHtml::encode($contrato->nombre);?></h3>

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
                        <td><b>RUT Mandante:</b></td>
                        <td><?php echo $form->textField($contrato,'rut_mandante',array('class'=>'upper')); ?></td>
                        <td><b>Nombre Mandante:</b></td>
                        <td><?php echo $form->textField($contrato,'nombre_mandante'); ?></td>
                </tr>
		
		<tr>
			<td><?php echo $form->labelEx($contrato,'nombre'); ?><?php echo $form->hiddenField($contrato,'id');?></td>
			<td><?php echo $form->textField($contrato,'nombre',
									array('class'=>'upper',
										  'ajax' => 
											array(	'type' =>'POST',
					                        		'url' => CController::createUrl('//contratos/existeContrato'),
					                        		'update' => '#error_contrato')
										  )); ?>
				<?php echo $form->error($contrato,'nombre');?>
				<div id="error_contrato" class="errorMessage"></div></td>
			<td><?php echo $form->labelEx($contrato,'fecha_inicio'); ?></td>
			<td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'tipos_contratos_id'); ?></td>
			<td><?php echo $form->dropDownList($contrato,'tipos_contratos_id', CHtml::listData(TiposContratos::model()->findAll(), 'id', 'nombre')); ?></td>
			<td><?php echo $form->labelEx($contrato,'tipos_reajustes_id'); ?></td>
			<td><?php echo $form->dropDownList($contrato,'tipos_reajustes_id', CHtml::listData(TiposReajustes::model()->findAll(), 'id', 'nombre')); ?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'presupuesto_oficial');?></td>
			<td><?php echo $form->textField($contrato,'presupuesto_oficial',array('class'=>'dinero'));?><?php echo $form->error($contrato,'presupuesto_oficial'); ?></td>
			<td><?php echo $form->labelEx($contrato,'codigo_safi');?></td>
			<td><?php echo $form->textField($contrato,'codigo_safi',array('class'=>''));?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'valor_neto');?></td>
			<td class='plata'><?php echo CHtml::encode($contrato->valor_neto);?></td>
		</tr>
		<tr>
			<td width="30"><?php echo $form->labelEx($contrato,'observacion'); ?></td>
			<td colspan="3"><?php echo $form->textArea($contrato,'observacion',array('class'=>'upper','cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?></td>
		</tr>
	</table>
	
	<fieldset>
		<legend>Resoluciones del Contrato</legend>
		<?php 
		$r=0;
		$i=0;
		$fr = 0;
		$primera = true;
		$total = 0;
		$porFila = 0;
		foreach($resoluciones as $res):?>
		<table class="tableResolucion">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table class="divResolucion">
									<tr>
										<td><b>N°Resolución:</b></td>
										<td><?php echo CHtml::encode($res->numero); ?></td>
										<td><b>Fecha Resolución:</b></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_resolucion)); ?></td>
										<td><b>Valor de Resolución u OC c/IVA:</b></td>
										<td class="plata"><?php echo CHtml::encode($res->monto); ?></td>
										<td><b>Plazo en Días:</b></td>
										<td><?php echo CHtml::encode($res->plazo); ?></td>
									</tr>
									<tr>
										<?php if($primera):
										$primera = false;?>
										<td><b>Fecha Inicio Obra:</b></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_inicio)); ?></td>
										<?php else:?>
										<td><b>Fecha Tramitada:</b></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_tramitada)); ?></td>
										<?php endif;?>
										<td><b>Fecha Final:</b></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_final)); ?><input type="hidden" value="<?php echo CHtml::encode(Tools::backFecha($res->fecha_final)); ?>" class="fecha_min"/></td>
										<td><b>Generada por:</b></td>
										<td><?php 
											$usuario = Usuarios::model()->findByPk($res->creador_id);
											echo CHtml::encode($usuario->nombre); 
											?></td>
										<td><b>Modificada por:</b></td>
										<td><?php 
											$usuario = Usuarios::model()->findByPk($res->modificador_id);
											echo CHtml::encode($usuario->nombre); 
											?></td>
									</tr>
									<tr>
										<td><b>Observación: </b></td>
										<td colspan="7"><?php echo CHtml::encode($res->observacion); ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					
					<?php
						if(isset($flujosProgramados[$res->id])):
						foreach($flujosProgramados[$res->id] as $flujoP):
							$eps = $flujoP->getEPs();
					?> 
					<table style="font-size:0.9em;">
						<tr>
							<td>
								<fieldset>
									<legend><?php echo CHtml::encode(Tools::getMes($flujoP->mes)); ?> <?php echo CHtml::encode($flujoP->agno); ?></legend>
									<table>
										<tr>
											<td width="70"><?php echo $form->label($flujoP,"[$i]produccion");?></td>
											<td width="100"><?php echo $form->textField($flujoP,"[$i]produccion",array('class'=>'dinero','size'=>9)); ?>
												<div id="errorProduccion<?php echo CHtml::encode($flujoP->id);?>" class="errorMessage" style="display:none;"></div>
											</td>
											<td width="70"><?php echo $form->label($flujoP,"[$i]costo");?></td>
											<td><?php echo $form->textField($flujoP,"[$i]costo",array('class'=>'dinero','size'=>9)); ?>
                                                                                            <div id="errorCosto<?php echo CHtml::encode($flujoP->id);?>" class="errorMessage" style="display:none;"></div>
											</td>
										</tr>
										<tr>
											<td colspan="4">
                                                                                            Comentarios Flujo Programado:<br/>
                                                                                            <?php echo $form->textArea($flujoP,"[$i]comentarios",array('cols'=>'90','rows'=>'1','style'=>'overflow:auto;resize:none;')); ?>
                                                                                            <?php echo $form->hiddenField($flujoP,"[$i]id"); ?>
                                                                                            <?php echo $form->hiddenField($flujoP,"[$i]resoluciones_id"); ?>
											</td>
										</tr>
									</table>
									<fieldset class="eps">
										<legend>EP</legend>
										Agregar un EP: 
										<?php echo CHtml::dropDownList('tipo','',
												array(	'obra' => 'EP de Obra', 
														'anticipo' => 'EP de anticipo', 
														'canje' => 'EP de canje de retenciones'),
														array('class'=>'tipoEP','i'=>$porFila));?>
										<div class="btn addEP" i="<?php echo $porFila;?>" resoluciones_id="<?php echo CHtml::encode($flujoP->resoluciones_id); ?>" mes="<?php echo CHtml::encode($flujoP->mes); ?>"  agno="<?php echo CHtml::encode($flujoP->agno); ?>">Agregar</div>
										<div class="agregados" id="agregados_<?php echo $porFila;?>">
										</div>	
										<?php 
										$i++;
										
										$flujos = array();
										if($eps != null){
											$flujos = Tools::getEP($eps,$flujoP->mes,$flujoP->agno,$flujoP->resoluciones_id);
										}
										
										foreach($flujos as $flujo){
											if($flujo['tipo']=='anticipo'){?>
												<div class="rodeado ep" j="<?php echo $total;?>" i="<?php echo $porFila;?>">
													<span><strong>EP de Anticipo</strong></span>
													<span>&nbsp;&nbsp;&nbsp;</span>
													<span>Valor: <input type="text" name="EpAnticipo[<?php echo $total;?>][valor]" value="<?php echo $flujo['ep']->valor?>" class="dinero"/></span>
													<span>&nbsp;</span>
													<span>Comentarios: <input type="text" name="EpAnticipo[<?php echo $total;?>][comentarios]" value="<?php echo $flujo['ep']->comentarios?>" size="50"/></span>
													<input type="hidden" name="EpAnticipo[<?php echo $total;?>][mes]" value="<?php echo $flujo['ep']->mes?>"/>
													<input type="hidden" name="EpAnticipo[<?php echo $total;?>][agno]" value="<?php echo $flujo['ep']->agno?>"/>
													<input type="hidden" name="EpAnticipo[<?php echo $total;?>][resoluciones_id]" value="<?php echo $flujo['ep']->resoluciones_id?>"/>
													<span>&nbsp;</span>
													<span tipo="anticipo" class="btn delEP" j="<?php echo $total;?>" i="<?php echo $porFila;?>">Eliminar</span>
												</div>
												<input type="hidden" name="EpAnticipo[<?php echo $total;?>][id]" value="<?php echo $flujo['ep']->id?>"/>
												<script>str_anticipo += '<?php echo $porFila;?>_';</script>
											<?php 
											}
											if($flujo['tipo']=='canje_retencion'){?>
												<div class="rodeado ep" j="<?php echo $total;?>" i="<?php echo $porFila;?>">
													<span><strong>EP Canje Retenciones</strong></span>
													<span>&nbsp;&nbsp;&nbsp;</span>
													<span>Valor: <input type="text" name="EpCanjeRetencion[<?php echo $total;?>][valor]" value="<?php echo $flujo['ep']->valor?>" class="dinero"/></span>
													<span>&nbsp;</span>
													<span>Comentarios: <input type="text" name="EpCanjeRetencion[<?php echo $total;?>][comentarios]" value="<?php echo $flujo['ep']->comentarios?>" size="43"/></span>
													<input type="hidden" name="EpCanjeRetencion[<?php echo $total;?>][mes]" value="<?php echo $flujo['ep']->mes?>"/>
													<input type="hidden" name="EpCanjeRetencion[<?php echo $total;?>][agno]" value="<?php echo $flujo['ep']->agno?>"/>
													<input type="hidden" name="EpCanjeRetencion[<?php echo $total;?>][resoluciones_id]" value="<?php echo $flujo['ep']->resoluciones_id?>"/>
													<span>&nbsp;</span>
													<span tipo="canje" class="btn delEP" j="<?php echo $total;?>" i="<?php echo $porFila;?>">Eliminar</span>
												</div>
												<input type="hidden" name="EpCanjeRetencion[<?php echo $total;?>][id]" value="<?php echo $flujo['ep']->id?>"/>
												<script>str_retencion += '<?php echo $porFila;?>_';</script>
											<?php 
											}
											if($flujo['tipo']=='obra'){?>
												<div class="rodeado ep" j="<?php echo $total;?>" i="<?php echo $porFila;?>">
													<span><strong>EP Obra</strong></span>
													<span>&nbsp;&nbsp;&nbsp;</span>
													<span>Producción: &nbsp;&nbsp;<input type="text" name="EpObra[<?php echo $total;?>][produccion]" value="<?php echo $flujo['ep']->produccion?>" class="dinero"/></span>
													<span>&nbsp;</span>
													<span>Costo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="EpObra[<?php echo $total;?>][costo]" value="<?php echo $flujo['ep']->costo?>" class="dinero"/></span>
													<span>&nbsp;</span>
													<span>Reajuste: <input type="text" name="EpObra[<?php echo $total;?>][reajuste]" value="<?php echo $flujo['ep']->reajuste?>" class="dinero"/></span>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>Retención: &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="EpObra[<?php echo $total;?>][retencion]" value="<?php echo $flujo['ep']->retencion?>" class="dinero"/></span>
													<span>&nbsp;</span>
													<span>Descuento: <input type="text" name="EpObra[<?php echo $total;?>][descuento]" value="<?php echo $flujo['ep']->descuento?>" class="dinero"/></span>
													<br/>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
													<span>Comentarios: <input type="text" name="EpObra[<?php echo $total;?>][comentarios]" value="<?php echo $flujo['ep']->comentarios?>" size="50"/></span>
													<input type="hidden" name="EpObra[<?php echo $total;?>][mes]" value="<?php echo $flujo['ep']->mes?>"/>
													<input type="hidden" name="EpObra[<?php echo $total;?>][agno]" value="<?php echo $flujo['ep']->agno?>"/>
													<input type="hidden" name="EpObra[<?php echo $total;?>][resoluciones_id]" value="<?php echo $flujo['ep']->resoluciones_id?>"/>
													<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
													<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
													<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
													<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
													<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
													<span tipo="canje" class="btn delEP" i="<?php echo $porFila;?>" j="<?php echo $total;?>">Eliminar</span>
                                                                                                        <input type="hidden" name="EpObra[<?php echo $total;?>][id]" value="<?php echo $flujo['ep']->id?>"/>
												</div>
											<?php 
											}
											$total++;
										}								
										
										?>	
									<?php $porFila++;?>					
									</fieldset>
									
								</fieldset>
							</td>
						</tr>
					</table>
					<?php 
						endforeach;
						endif;
					?>
				</td>
			</tr>
		</table>
		<?php 
		$r++;
		endforeach;?>
	</fieldset>
	<input type="hidden" id="total" value="<?php echo $total;?>"/>
	<input type="hidden" id="i" value="<?php echo $porFila;?>"/>

	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


