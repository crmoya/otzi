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


		$("#guardar").click(function(){
			var valid = true;
			valid = $("#error_res").html() == "";
			valid = valid && checkFechas(2);
			valid = valid && checkFechaInicio();
			valid = valid && checkCompareFechas();
			valid = valid && checkProducciones();
			return valid;
		});
		
		
			
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Adjudicación de Contrato:</h3>

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
	'id'=>'adjudicacion-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>

	<p class="note" id="note">
		Campos con <span class="required">*</span> son requeridos.
	</p>

	<fieldset>
		<legend>Contrato</legend>
		<table>
                        <tr>
				<td><?php echo $form->labelEx($contrato,'rut_mandante'); ?></td><td><?php echo CHtml::encode($contrato->rut_mandante);?></td>
				<td><?php echo $form->labelEx($contrato,'nombre_mandante'); ?></td><td><?php echo CHtml::encode($contrato->nombre_mandante);?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($contrato,'nombre'); ?></td><td><?php echo CHtml::encode($contrato->nombre);?></td>
				<td><?php echo $form->labelEx($contrato,'fecha_inicio'); ?></td><td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
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
				<td width="150px"><?php echo $form->labelEx($contrato,'presupuesto_oficial');?></td>
				<td class="plata"><?php echo CHtml::encode($contrato->presupuesto_oficial);?></td>
				<td><?php echo $form->labelEx($contrato,'codigo_safi');?></td>
				<td><?php echo CHtml::encode($contrato->codigo_safi);?></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset id="fs">
		<legend>Resolución de Adjudicación</legend>
		<table>
			<tr>
				<td><?php echo $form->labelEx($resolucion,'numero'); ?></td>
				<td><?php echo $form->textField($resolucion,'numero',
									array('class'=>'upper',
										  'ajax' => 
											array(	'type' =>'POST',
					                        		'url' => CController::createUrl('//contratos/existeResolucion'),
					                        		'update' => '#error_res')
										  )); ?>
					<?php echo $form->error($resolucion,'numero'); ?><div id="error_res" class="errorMessage"></div></td>
				<td><?php echo $form->labelEx($resolucion,'monto'); ?></td>
				<td><?php echo $form->textField($resolucion,'monto',array('class'=>'dinero')); ?><?php echo $form->error($resolucion,'monto'); ?></td>
			</tr>
			<tr>
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
								'value'=>date('d/m/Y'),
								'class'=>'fecha_inicio fecha fecha_inicial',
								'nId'=>'0',
						    ),
						)
					);
					?>
					<br/><div id="errorFecha0" class="errorMessage" style="display:none;"></div>
				</td>
					
				<td><?php echo $form->labelEx($resolucion,'fecha_inicio'); ?></td>
				<td>
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',
						array(
							'model'=>$resolucion,
							'language' => 'es',
							'attribute'=>'fecha_inicio',
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>'dd/mm/yy',
								'changeYear'=>true,
								'changeMonth'=>true,
							),
							'htmlOptions'=>array(
						        'style'=>'width:70px;',
								'class'=>'fecha fecha_inicial fecha_inicio_obra',
								'nId'=>'1',	
						    ),
						)
					);
					?>
					<br/><div id="errorFecha1" class="errorMessage" style="display:none;"></div>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($resolucion,'plazo'); ?></td>
				<td><?php echo $form->textField($resolucion,'plazo',array('class'=>'fixedMayor0 plazo')); ?><?php echo $form->error($resolucion,'plazo'); ?></td>
				<td><?php echo $form->labelEx($resolucion,'fecha_final'); ?></td><td><?php echo $form->textField($resolucion,'fecha_final',array('readonly'=>'readonly','class'=>'fecha_final','style'=>'width:70px;')); ?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($resolucion,'observacion'); ?></td>
				<td colspan="3"><?php echo $form->textArea($resolucion,'observacion',array('class'=>'upper','cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?>
				<?php echo $form->error($resolucion,'observacion'); ?></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset id="flujos">
		<legend>Flujos de Producción Programada</legend>
		<div class="complex">
			<table>
				<tr>
					<td style="vertical-align:top;">
						<div>
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
															  <td><?php echo $form->labelEx($flujo,"mes",array('style'=>'width:80px;'));?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]mes',array('id'=>"mes{0}",'class'=>'mes','readonly'=>'readonly'));?>
															  	  <input type="hidden" class="rowIndex" value="{0}" />
															  </td>
															  <td><?php echo $form->labelEx($flujo,"agno",array('style'=>'width:80px;'));?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]agno',array('id'=>"agno{0}",'class'=>'agno','readonly'=>'readonly'));?>
															  </td>
															</tr>
															<tr>
															  <td><?php echo $form->labelEx($flujo,"produccion",array('class'=>'plata','style'=>'width:80px;'));?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]produccion',array('id'=>"produccion{0}",'class'=>'dinero produccion'));?>
															  	  <br/><div id="errorProduccion{0}" class="errorMessage" style="display:none;"></div>
															  </td>
															  <td><?php echo $form->labelEx($flujo,"costo",array('class'=>'plata','style'=>'width:80px;'));?></td>
															  <td><?php echo $form->textField($flujo,'[{0}]costo',array('id'=>"costo{0}",'class'=>'dinero costo'));?>
															  </td>
															</tr>
															<tr>
															  <td><?php echo $form->labelEx($flujo,'comentarios'); ?></td>
															  <td colspan="3">
															  	<?php echo $form->textField($flujo,'[{0}]comentarios',array('class'=>'upper','size'=>100)); ?>
															  </td>
															</tr>
															<tr>
															  <td style="font-size:0.9em;"><b>Producción Programada Acumulada Neta:</b></td>
															  <td id="prodAcum{0}"></td>
															  <td style="font-size:0.9em;"><b>Costo Programado Acumulado Neto:</b></td>
															  <td id="costoAcum{0}"></td>
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
	
	

	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->
