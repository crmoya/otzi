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
       
                //escondeFlujos();

		$('.fecha_inicio').change(function(e){
                    //escondeFlujos();
		});
                
                $(document).on('change','.plazoR',function(e){
                    var f_inicial = getFecha($('.fecha_inicio_obra').val());
                    var plazo = $(this).val();
                    calculaFechaFinal(f_inicial, plazo);
                    escondeFlujosFinal();
                });
		
                function escondeFlujosFinal(){
                    var fecha = $('.fecha_final').val();
                    var fecha_arr = fecha.split('/');
                    var m_fin = -1;
                    var a_fin = -1;
                    if(fecha_arr.length == 3){
                        try{
                            m_fin = parseInt(fecha_arr[1]);
                            a_fin = parseInt(fecha_arr[2]);
                        }catch(e){}
                    }

                    $('.flujo').each(function(){
                        $(this).show();
                        var mes = $(this).attr("mes");
                        var agno = $(this).attr("agno");
                        if(agno > a_fin){
                            $(this).remove();
                        }else if(agno == a_fin){
                            if(mes > m_fin){
                                $(this).remove();
                            }
                        }
                    });
                }
		
		mIni = '<?php echo CHtml::encode($mesInicio);?>';
		aIni = '<?php echo CHtml::encode($agnoInicio);?>';
		
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
                        <td><?php echo CHtml::encode($contrato->rut_mandante);?></td>
                        <td><b>Nombre Mandante:</b></td>
                        <td><?php echo CHtml::encode($contrato->nombre_mandante);?></td>
                </tr>
		
		<tr>
			<td><?php echo $form->labelEx($contrato,'nombre'); ?><?php echo $form->hiddenField($contrato,'id');?></td>
			<td><?php echo CHtml::encode($contrato->nombre); ?></td>
			<td><?php echo $form->labelEx($contrato,'fecha_inicio'); ?></td>
			<td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
			<td><?php echo $form->labelEx($contrato,'tipos_contratos_id'); ?></td>
			<td><?php $tipo_contrato = TiposContratos::model()->findByPk($contrato->tipos_contratos_id);
					if($tipo_contrato != null)
						echo CHtml::encode($tipo_contrato->nombre); ?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'tipos_reajustes_id'); ?></td>
			<td><?php 
					$tipo_reajuste= TiposReajustes::model()->findByPk($contrato->tipos_reajustes_id);
					if($tipo_reajuste != null)
						echo CHtml::encode($tipo_reajuste->nombre); ?></td>
			<td><?php echo $form->labelEx($contrato,'presupuesto_oficial');?></td>
			<td class='plata'><?php echo CHtml::encode($contrato->presupuesto_oficial); ?></td>
			<td><?php echo $form->labelEx($contrato,'codigo_safi');?></td>
			<td><?php echo CHtml::encode($contrato->codigo_safi);?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($contrato,'valor_neto');?></td>
			<td class='plata'><?php echo CHtml::encode($contrato->valor_neto);?></td>
			<td width="30"><?php echo $form->labelEx($contrato,'observacion'); ?></td>
			<td colspan="3"><?php echo CHtml::encode($contrato->observacion);?></td>
		</tr>
	</table>
	
	<fieldset>
		<legend>Última Resolución del Contrato</legend>
		<?php 
		$r=0;
		$i=0;
		$fr = 0;
		if($ultima_res!=null):?>
		<table class="tableResolucion">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table class="divResolucion">
									<tr>
										<td><b>N°Resolución:</b></td>
										<td><?php echo $form->textField($ultima_res,"numero",array('class'=>'upper',)); ?></td>
										<td><b>Fecha Resolución:</b><?php echo $form->hiddenField($ultima_res,'fecha_inicio',array('class'=>'fecha_inicio_obra fecha fecha_inicial','value'=>Tools::backFecha($ultima_res->fecha_inicio)));?></td>
										<td>
											<?php
												$this->widget('zii.widgets.jui.CJuiDatePicker',
													array(
														'model'=>$ultima_res,
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
															'value'=>Tools::backFecha($ultima_res->fecha_resolucion),
															'class'=>'fecha fecha_inicio fecha_inicial',
															'nId'=>'0',
															'readonly'=>'readonly',
													    ),
													)
												);
												
												?>
											<br/><div id="errorFecha0" class="errorMessage" style="display:none;"></div>
										</td>
										<td><b>Valor de Resolución u OC c/IVA:</b></td>
										<td class="dinero"><?php echo $form->textField($ultima_res,'monto',array("size"=>'9','class'=>'dinero')); ?></td>
										<td><b>Plazo en Días:</b></td>
										<td><?php echo $form->textField($ultima_res,'plazo',array("size"=>'6','class'=>'fixedMayor0 plazoR')); ?></td>
									</tr>
									<tr>
										<td><b>Fecha Tramitada:</b></td>
										<td>
											<?php
												$this->widget('zii.widgets.jui.CJuiDatePicker',
													array(
														'model'=>$ultima_res,
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
															'value'=>Tools::backFecha($ultima_res->fecha_tramitada),
															'class'=>'fecha',
															'nId'=>'1',
															'readonly'=>'readonly',
													    ),
													)
												);
												
												?>
												<div id="errorFecha1" class="errorMessage" style="display:none;"></div>
										</td>
										<td><b>Nueva Fecha Contrato:</b></td>
										<td><?php echo $form->textField($ultima_res,'fecha_final',array('value'=>Tools::backFecha($ultima_res->fecha_final),'readonly'=>'readonly','class'=>'fecha_final','style'=>'width:70px;')); ?></td>
										<td><b>Generada por:</b></td>
										<td><?php 
											$usuario = Usuarios::model()->findByPk($ultima_res->creador_id);
											if($usuario!=null)echo CHtml::encode($usuario->nombre); 
											else echo $ultima_res->creador_id;
											?></td>
										<td><b>Modificada por:</b></td>
										<td><?php 
											$usuario = Usuarios::model()->findByPk($ultima_res->modificador_id);
											if($usuario!=null)echo CHtml::encode($usuario->nombre); 
											else echo $ultima_res->creador_id;
											?></td>
									</tr>
									<tr>
										<td><b>Observación: </b></td>
										<td colspan="7"><?php echo $form->textArea($ultima_res,'observacion',array('class'=>'upper','cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>
		<?php 
		$r++;
		endif;?>
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
												  <td><?php echo $form->labelEx($fProg,"mes");?><?php echo $form->hiddenField($fProg,"[$i]id");?></td>
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
		

	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


