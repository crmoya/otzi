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

		escondeFlujos();

		$('.fecha_inicio').change(function(e){
                    escondeFlujos();
		});
		
		
		mIni = '<?php echo CHtml::encode($mesInicio);?>';
		aIni = '<?php echo CHtml::encode($agnoInicio);?>';
		$("#guardar").click(function(){
			var valid = true;
			valid = $("#error_res").html() == "";
			valid = valid && checkFechas(1);
			valid = valid && checkFechaInicio();
			//valid = valid && checkProducciones();
			return valid;
		});

		$('.mes').each(function(){
			var m = new Number($(this).val());
			m = m+1;
			m--;
			$(this).val(labelMes(m));	
		});

		mIngresados = '<?php echo count($flujosP);?>';
		calculaSumaProd();
		calculaSumaCosto();

		$('.dinero').each(function() {
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
<h3>Agregar nueva Resolución a <?php echo CHtml::encode($contrato->nombre);?></h3>

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
			<td><?php echo $form->labelEx($contrato,'presupuesto_oficial');?></td>
			<td class="plata"><?php echo CHtml::encode($contrato->presupuesto_oficial);?></td>
			<td><?php echo $form->labelEx($contrato,'codigo_safi');?></td>
			<td><?php echo CHtml::encode($contrato->codigo_safi);?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'valor_neto');?></td>
			<td class='plata'><?php echo CHtml::encode($contrato->valor_neto);?></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<br/>
	
	<fieldset id="fs">
		<legend>Agregar Resolución</legend>
		<table>
			<tr>
				<td><?php echo $form->labelEx($resolucion,'numero'); ?></td>
				<td><?php echo $form->textField($resolucion,'numero',
									array('class'=>'upper',
										  'ajax' => 
											array(	'type' =>'POST',
					                        		'url' => CController::createUrl('//contratos/existeResolucion'),
					                        		'update' => '#error_res')
										  )); ?><?php echo $form->error($resolucion,'numero'); ?><div id="error_res" class="errorMessage"></div></td>
				<td><?php echo $form->labelEx($resolucion,'fecha_resolucion'); ?></td>
				<td>
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',
						array(
							'model'=>$resolucion,
							'language' => 'es',
							'attribute'=>'fecha_resolucion',
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>'dd/mm/yy',
								'changeYear'=>true,
								'changeMonth'=>true,
							),
							'htmlOptions'=>array(
						        'style'=>'width:70px;',			
								'value'=>date("d/m/Y"),
								'class'=>'fecha_inicio fecha fecha_inicial',
								'readonly'=>'readonly',
								'nId'=>'0',
						    ),
						)
					);
					
					?>
					<input type='hidden' name='fecha_inicio_obra' class='fecha_inicio_obra' value='<?php echo Tools::backFecha($ultima_res->fecha_final);?>'/>
					<br/><div id="errorFecha0" class="errorMessage" style="display:none;"></div>
				</td>
				<td><!--  <?php echo $form->labelEx($resolucion,'monto'); ?> --><b>Nuevo valor del contrato c/IVA</b></td>
				<td><?php echo $form->textField($resolucion,'monto',array('class'=>'dinero')); ?><?php echo $form->error($resolucion,'monto'); ?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($resolucion,'plazo'); ?></td>
				<td><?php echo $form->textField($resolucion,'plazo',array('class'=>'fixedMayor0 plazo')); ?><?php echo $form->error($resolucion,'plazo'); ?></td>	
				<td class="label">Nueva Fecha Contrato:</td>
				<td><?php echo $form->textField($resolucion,'fecha_final',array('readonly'=>'readonly','class'=>'fecha_final','style'=>'width:70px;')); ?></td>
				<td><?php echo $form->labelEx($resolucion,'fecha_tramitada'); ?></td>
				<td>
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',
						array(
							'model'=>$resolucion,
							'language' => 'es',
							'attribute'=>'fecha_tramitada',
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>'dd/mm/yy',
								'changeYear'=>true,
								'changeMonth'=>true,
							),
							'htmlOptions'=>array(
						        'style'=>'width:70px;',			
								'value'=>date("d/m/Y"),
								'class'=>'fecha',
								'readonly'=>'readonly',
								'nId'=>'1',
						    ),
						)
					);
					
					?>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($resolucion,'observacion'); ?></td>
				<td colspan="5"><?php echo $form->textArea($resolucion,'observacion',array('class'=>'upper','cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset id="flujos">
		<legend>Nuevos Flujos de Producción Programada</legend>
		<div class="complex">
			<table>
				<tr>
					<td style="vertical-align:top;">
						<div>
							<table class="templateFrame grid" cellspacing="0">
								<tbody class="templateTarget">
									<?php foreach($flujosP as $i=>$fProg): ?>
									<tr class="templateContent">
										<td width="100px">
											<table style="border:solid 1px silver;padding:10px;" class="flujo" mes="<?php echo $fProg->mes;?>" agno="<?php echo $fProg->agno;?>">
												<tr>
												  <td><?php echo $form->labelEx($fProg,"mes");?></td>
												  <td><?php echo $form->textField($fProg,"[$i]mes",array('size'=>6,'class'=>'mes','readonly'=>'readonly'));?></td>
												  <td><?php echo $form->labelEx($fProg,"agno");?></td>
												  <td><?php echo $form->textField($fProg,"[$i]agno",array('size'=>6,'readonly'=>'readonly'));?></td>
												  <td><?php echo $form->labelEx($fProg,"produccion");?></td>
												  <td><?php echo $form->textField($fProg,"[$i]produccion",array('size'=>9,'class'=>'dinero produccion','id'=>"produccion$i"));?></td>
												  <td><?php echo $form->labelEx($fProg,"costo");?></td>
												  <td><?php echo $form->textField($fProg,"[$i]costo",array('size'=>9,'class'=>'dinero costo','id'=>"costo$i"));?>
												  	<input type="hidden" class="rowIndex" value="<?php echo $i;?>" /></td>
												</tr>
												<tr> 
												  <td><?php echo $form->labelEx($fProg,"comentarios");?></td>
												  <td colspan="7"><?php echo $form->textField($fProg,"[$i]comentarios",array('class'=>'upper','size'=>100));?></td>														  
												</tr>
												<tr>
												  <td colspan="2" style="font-size:0.9em;"><b>Producción Programada<br/>Acumulada Neta</b></td>
												  <td colspan="2" id="prodAcum<?php echo $i;?>"></td>
												  <td colspan="2" style="font-size:0.9em;"><b>Costo Programado<br/>Acumulado Neto</b></td>
												  <td id="costoAcum<?php echo $i;?>"></td>
												  <td></td>
												</tr>												
											</table>	
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<table class="templateFrame grid" cellspacing="0">
								<tbody class="templateTarget">
									
								</tbody>
								<tfoot>
									<tr>
										<td>
											<div class="add" style="display:none;"></div>
											<textarea class="template" rows="0" cols="0">
												<tr class="templateContent">
													<td width="100px">
														<?php $flujo = new FlujosProgramados();?>
														<table style="border:solid 1px silver;padding:10px;">
															<tr>
															  <td><?php echo $form->labelEx($flujo,"mes");?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]mes',array('size'=>6,'id'=>"mes{0}",'class'=>'mes','readonly'=>'readonly'));?>
															  	  <input type="hidden" class="rowIndex" value="{0}" />
															  </td>
															  <td><?php echo $form->labelEx($flujo,"agno");?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]agno',array('size'=>6,'id'=>"agno{0}",'class'=>'agno','readonly'=>'readonly'));?>
															  </td>
															  <td><?php echo $form->labelEx($flujo,"produccion");?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]produccion',array('size'=>9,'id'=>"produccion{0}",'class'=>'dinero produccion'));?>
															  	  <br/><div id="errorProduccion{0}" class="errorMessage" style="display:none;"></div>
															  </td>
															  <td><?php echo $form->labelEx($flujo,"costo");?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]costo',array('size'=>9,'id'=>"costo{0}",'class'=>'dinero costo'));?>
															  </td>
															</tr>
															<tr>
															  <td><?php echo $form->labelEx($flujo,'comentarios'); ?></td>
															  <td colspan="7">
															  	<?php echo $form->textField($flujo,'[{0}]comentarios',array('class'=>'upper','size'=>100)); ?>
															  </td>
															</tr>
															<tr>
															  <td colspan="2" style="font-size:0.9em;"><b>Producción Programada<br/>Acumulada Neta</b></td>
															  <td colspan="2" id="prodAcum{0}"></td>
															  <td colspan="2" style="font-size:0.9em;"><b>Costo Programado<br/>Acumulado Neto</b></td>
															  <td id="costoAcum{0}"></td>
															  <td></td>
															</tr>	
														</table>	
													</td>
												</tr>
											</textarea>
										</td>
									</tr>
								</tfoot>
							</table>
						</div><!--panel-->
					</td>
				</tr>
			</table>
		</div><!--complex-->
	</fieldset>
	<fieldset>
		<legend>Resoluciones que ya tiene el Contrato</legend>
		<?php 
		$r=0;
		$primeraRes = true;
		foreach($resoluciones_antiguas as $res):?>
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
										<?php if($primeraRes):
												$primeraRes = false;	
										?>
										<td><b>Fecha Inicio Obra:</b></td>
										<td><?php echo CHtml::encode(Tools::backFecha($res->fecha_inicio)); ?></td>
										<?php else: ?>
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
						<?php 
							if(isset($flujosProgramados[$res->id])):
								foreach($flujosProgramados[$res->id] as $flujoP):
									$eps = $flujoP->getEPs();
							?>
							<tr>
								<td>
									<fieldset class="flujoReal2">
										<legend><?php echo CHtml::encode(Tools::getMes($flujoP->mes)); ?> <?php echo CHtml::encode($flujoP->agno); ?></legend>
										<table>	
											<tr>
												<td><?php echo $form->label($flujoP,"produccion");?><span class="plata"><?php echo CHtml::encode($flujoP->produccion); ?></span></td>
												<td><?php echo $form->label($flujoP,"costo");?><span class="plata"><?php echo CHtml::encode($flujoP->costo); ?></span></td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>									
											<tr>
												<td><b>Comentarios:</b></td>
												<td colspan="4"><?php echo CHtml::encode($flujoP->comentarios); ?></td>
											</tr>
										</table>
										<?php
										$flujos = array(); 
										if($eps!=null){
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
		

	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


