<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 

?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faena-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($model); ?>
		
	

	<div class="row">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'vigente'); ?>
	    <?php echo $form->dropDownList($model,'vigente', CHtml::listData(array(array('id'=>'SÍ','nombre'=>'SÍ'),array('id'=>'NO','nombre'=>'NO')), 'id', 'nombre')); ?>
	  	<?php echo $form->error($model,'vigente'); ?>
	</div>
	
	<div class="complex">
		<table>
			<tr>
				<td style="vertical-align:top;">
					<div>
						<table class="templateFrame grid" cellspacing="0">
							<tbody class="templateTarget">
								<tr>
								 	<td>Origen</td>
									<td>Destino</td>
									<td>PU</td>
									<td>KMs</td>
									<td>&nbsp</td>
								</tr>
							<?php 
							if(isset($ods)){	
								foreach($ods as $i=>$od): ?>
								<tr class="templateContent">
									<td>
										<?php 
										echo $form->dropDownList($od,"[$i]origen",CHtml::listData(Origen::model()->listar($od->origen_id), 'id', 'nombre'),array('style'=>'width:100px'));  
										?>
									</td>
									<td>
										<?php 
										echo $form->dropDownList($od,"[$i]destino",CHtml::listData(Destino::model()->listar($od->destino_id), 'id', 'nombre'),array('style'=>'width:100px'));
										?>
									</td>
									<td>
										<?php 
										echo $form->textField($od,"[$i]pu",array('style'=>'width:100px','class'=>'fixed')); 
										?>
									</td>
									<td>
										<?php 
										echo $form->textField($od,"[$i]kmRecorridos",array('style'=>'width:100px','class'=>'fixed')); 
										?>
									</td>
									<td>
										<input type="hidden" class="rowIndex" value="<?php echo $i;?>" />
										<div class="remove">Eliminar</div>
										<input type="hidden" name="OrigendestinoFaena[<?php echo $i;?>][id]" value="<?php echo $od->id;?>" />
									</td>
								</tr>
							<?php 
								endforeach; 
							}?>
							</tbody>
							<tfoot>
								<tr>
									<td>
										<div class="add">Agregar PU por volumen</div>
										<textarea class="template" rows="0" cols="0">
											<tr class="templateContent">
												<td width="100px">	
													<?php echo CHtml::dropDownList('OrigendestinoFaena[{0}][origen]','',CHtml::listData(Origen::model()->listar(), 'id', 'nombre'),array('style'=>'width:100px')); ?>
												</td>
												<td width="100px">
													<?php echo CHtml::dropDownList('OrigendestinoFaena[{0}][destino]','',CHtml::listData(Destino::model()->listar(), 'id', 'nombre'),array('style'=>'width:100px')); ?>
												</td>
												<td width="100px">
													<?php echo CHtml::textField('OrigendestinoFaena[{0}][pu]','',array('style'=>'width:100px','class'=>'fixed')); ?>
												</td>
												<td width="100px">
													<?php echo CHtml::textField('OrigendestinoFaena[{0}][kmRecorridos]','',array('style'=>'width:100px','class'=>'fixed')); ?>
												</td>
												<td>
													<input type="hidden" class="rowIndex" value="{0}" />
													<div class="remove">Eliminar</div>
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

	<h4>Precios unitarios de la faena para Camiones y Camionetas Propios o Arrendados</h4>

	<div class="complex">
		<table>
			<tr>
				<td style="vertical-align:top;">
					<div>
						<table class="templateFrame grid" cellspacing="0">
							<tbody class="templateTarget">
								<tr>
									<td>Camión o camioneta</td>									
									<td>Unidad</td>
									<td>PU</td>
									<td>&nbsp</td>
								</tr>
							<?php 
							if(isset($unidades)){	
								foreach($unidades as $i=>$u): ?>
								<tr class="templateContent">
									<td>
									<table>
										<tr>
											<td>
												<?php
													$tipo = "";
													if((int)$u->camionpropio_id > 0){
														$tipo = "propio";
													}
													if((int)$u->camionarrendado_id > 0){
														$tipo = "arrendado";
													}
												?>
												<input id="tipo_camionpropio<?=$i?>" i="<?=$i?>" type="radio" class="tipo_camion" name="Unidadfaena[<?=$i?>][tipo_camion]" value="propios" <?=($tipo=="propio")?"checked='checked'":'';?>> Propios</br>
												<input id="tipo_camionarrendado<?=$i?>" i="<?=$i?>" type="radio" class="tipo_camion" name="Unidadfaena[<?=$i?>][tipo_camion]" value="arrendados" <?=($tipo=="arrendado")?"checked='checked'":'';?>> Arrendados
											</td>
											<td>
												<?php 
												echo CHtml::dropDownList("Unidadfaena[".$i."][camionpropio_id]",'',CHtml::listData(CamionPropio::model()->findAllByAttributes(['vigente'=>'SÍ'],['order'=>'nombre']), 'id', 'nombre'),array('style'=>'width:150px;','empty'=>'Seleccione Camión Propio','class'=>'camiones_propios'.$i, 'options'=>[$u->camionpropio_id=>['selected'=>true]]));  
												?>
												<?php 
												echo CHtml::dropDownList("Unidadfaena[".$i."][camionarrendado_id]",'',CHtml::listData(CamionArrendado::model()->findAllByAttributes(['vigente'=>'SÍ'],['order'=>'nombre']), 'id', 'nombre'),array('style'=>'width:150px;','empty'=>'Seleccione Camión Arrendado','class'=>'camiones_arrendados'.$i, 'options'=>[$u->camionarrendado_id=>['selected'=>true]]));  
												?>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												Observaciones:
												<input type="text" style="width:100%;" name="Unidadfaena[<?=$i?>][observaciones]" value="<?=$u->observaciones?>">
											</td>
										</tr>
									</table>
									</td>
									<td>
										<?php 
										echo $form->dropDownList($u,"[$i]unidad",CHtml::listData(Unidadfaena::listar(), 'id', 'nombre'),array('style'=>'width:100px'));  
										?>
									</td>
									<td>
										<?php 
										echo $form->textField($u,"[$i]pu",array('style'=>'width:100px','class'=>'fixed')); 
										?>
									</td>
									<td>
										<input type="hidden" class="rowIndex" value="<?php echo $i;?>" />
										<div class="remove">Eliminar</div>
										<input type="hidden" name="Unidadfaena[<?php echo $i;?>][id]" value="<?php echo $u->id;?>" />
									</td>
								</tr>
							<?php 
								endforeach; 
							}?>
							</tbody>
							<tfoot>
								<tr>
									<td>
										<div class="add">Agregar PU por tiempo</div>
										<textarea class="template" rows="0" cols="0">
											<tr class="templateContent">
												<td>
													<table>
														<tr>
															<td>
																<input id="tipo_camionpropio{0}"  i="{0}" type="radio" class="tipo_camion" name="Unidadfaena[{0}][tipo_camion]" value="propios"> Propios</br>
																<input id="tipo_camionarrendado{0}"  i="{0}" type="radio" class="tipo_camion" name="Unidadfaena[{0}][tipo_camion]" value="arrendados"> Arrendados
															</td>
															<td>
																<?php 
																echo CHtml::dropDownList("Unidadfaena[{0}][camionpropio_id]",'',CHtml::listData(CamionPropio::model()->findAllByAttributes(['vigente'=>'SÍ'],['order'=>'nombre']), 'id', 'nombre'),array('style'=>'width:150px;display:none;','empty'=>'Seleccione Camión Propio','class'=>'camiones_propios{0}'));  
																?>
																<?php 
																echo CHtml::dropDownList("Unidadfaena[{0}][camionarrendado_id]",'',CHtml::listData(CamionArrendado::model()->findAllByAttributes(['vigente'=>'SÍ'],['order'=>'nombre']), 'id', 'nombre'),array('style'=>'width:150px;display:none;','empty'=>'Seleccione Camión Arrendado','class'=>'camiones_arrendados{0}'));  
																?>
															</td>
														</tr>
														<tr>
															<td colspan="2">
																Observaciones:
																<input type="text" style="width:100%;" name="Unidadfaena[{0}][observaciones]">
															</td>
														</tr>
													</table>													
												</td>
												<td width="100px">	
													<?php echo CHtml::dropDownList('Unidadfaena[{0}][unidad]','',CHtml::listData(Unidadfaena::listar(), 'id', 'nombre'),array('style'=>'width:100px')); ?>
												</td>
												<td width="100px">
													<?php echo CHtml::textField('Unidadfaena[{0}][pu]','',array('style'=>'width:100px','class'=>'fixed unidad')); ?>
												</td>
												<td>
													<input type="hidden" class="rowIndex" value="{0}" />
													<div class="remove">Eliminar</div>
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

	<h4>Precios unitarios de la faena para Equipos Propios o Arrendados</h4>

	<div class="complex">
		<table>
			<tr>
				<td style="vertical-align:top;">
					<div>
						<table class="templateFrame grid" cellspacing="0">
							<tbody class="templateTarget">
								<tr>
									<td>Equipo</td>									
									<td>Unidad</td>
									<td>PU</td>
									<td>Hrs.Mínimas</td>
									<td>&nbsp</td>
								</tr>
							<?php 
							if(isset($unidadesE)){	
								foreach($unidadesE as $i=>$ue): ?>
								<tr class="templateContent">
									<td>
									<table>
										<tr>
											<td>
												<?php
													$tipo = "";
													if((int)$ue->equipopropio_id > 0){
														$tipo = "propio";
													}
													if((int)$ue->equipoarrendado_id > 0){
														$tipo = "arrendado";
													}
												?>
												<input id="tipo_equipopropio<?=$i?>" i="<?=$i?>" type="radio" class="tipo_equipo" name="UnidadfaenaEquipo[<?=$i?>][tipo_equipo]" value="propios" <?=($tipo=="propio")?"checked='checked'":'';?>> Propios</br>
												<input id="tipo_equipoarrendado<?=$i?>" i="<?=$i?>" type="radio" class="tipo_equipo" name="UnidadfaenaEquipo[<?=$i?>][tipo_equipo]" value="arrendados" <?=($tipo=="arrendado")?"checked='checked'":'';?>> Arrendados
											</td>
											<td>
												<?php 
												echo CHtml::dropDownList("UnidadfaenaEquipo[".$i."][equipopropio_id]",'',CHtml::listData(EquipoPropio::model()->list($ue->equipopropio_id),'id','nombre'),array('style'=>'width:150px;','empty'=>'Seleccione Equipo Propio','class'=>'equipos_propios'.$i, 'options'=>[$ue->equipopropio_id=>['selected'=>true]]));  
												?>
												<?php 
												echo CHtml::dropDownList("UnidadfaenaEquipo[".$i."][equipoarrendado_id]",'',CHtml::listData(EquipoArrendado::model()->list($ue->equipoarrendado_id), 'id', 'nombre'),array('style'=>'width:150px;','empty'=>'Seleccione Equipo Arrendado','class'=>'equipos_arrendados'.$i, 'options'=>[$ue->equipoarrendado_id=>['selected'=>true]]));  
												?>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												Observaciones:
												<input type="text" style="width:100%;" name="UnidadfaenaEquipo[<?=$i?>][observaciones]" value="<?=$ue->observaciones?>">
											</td>
										</tr>
									</table>
									</td>
									<td>
										<?php 
										echo $form->dropDownList($ue,"[$i]unidad",CHtml::listData(UnidadfaenaEquipo::listar(), 'id', 'nombre'),array('style'=>'width:100px'));  
										?>
									</td>
									<td>
										<?php 
										echo $form->textField($ue,"[$i]pu",array('style'=>'width:100px','class'=>'fixed')); 
										?>
									</td>
									<td>
										<?php 
										echo $form->textField($ue,"[$i]horas_minimas",array('style'=>'width:100px','class'=>'fixed')); 
										?>
									</td>
									<td>
										<input type="hidden" class="rowIndex" value="<?php echo $i;?>" />
										<div class="remove">Eliminar</div>
										<input type="hidden" name="UnidadfaenaEquipo[<?php echo $i;?>][id]" value="<?php echo $ue->id;?>" />
									</td>
								</tr>
							<?php 
								endforeach; 
							}?>
							</tbody>
							<tfoot>
								<tr>
									<td>
										<div class="add">Agregar PU por tiempo</div>
										<textarea class="template" rows="0" cols="0">
											<tr class="templateContent">
												<td>
													<table>
														<tr>
															<td>
																<input id="tipo_equipopropio{0}" i="{0}" type="radio" class="tipo_equipo" name="UnidadfaenaEquipo[{0}][tipo_equipo]" value="propios"> Propios</br>
																<input id="tipo_equipoarrendado{0}" i="{0}" type="radio" class="tipo_equipo" name="UnidadfaenaEquipo[{0}][tipo_equipo]" value="arrendados"> Arrendados
															</td>
															<td>
																<?php 
																echo CHtml::dropDownList("UnidadfaenaEquipo[{0}][equipopropio_id]",'',CHtml::listData(EquipoPropio::model()->findAllByAttributes(['vigente'=>'SÍ'],['order'=>'nombre']), 'id', 'nombre'),array('style'=>'width:150px;display:none;','empty'=>'Seleccione Equipo Propio','class'=>'equipos_propios{0}'));  
																?>
																<?php 
																echo CHtml::dropDownList("UnidadfaenaEquipo[{0}][equipoarrendado_id]",'',CHtml::listData(EquipoArrendado::model()->findAllByAttributes(['vigente'=>'SÍ'],['order'=>'nombre']), 'id', 'nombre'),array('style'=>'width:150px;display:none;','empty'=>'Seleccione Equipo Arrendado','class'=>'equipos_arrendados{0}'));  
																?>
															</td>
														</tr>
														<tr>
															<td colspan="2">
																Observaciones:
																<input type="text" style="width:100%;" name="UnidadfaenaEquipo[{0}][observaciones]">
															</td>
														</tr>
													</table>
													
												</td>
												<td width="100px">	
													<?php echo CHtml::dropDownList('UnidadfaenaEquipo[{0}][unidad]','',CHtml::listData(UnidadfaenaEquipo::listar(), 'id', 'nombre'),array('style'=>'width:100px')); ?>
												</td>
												<td width="100px">
													<?php echo CHtml::textField('UnidadfaenaEquipo[{0}][pu]','',array('style'=>'width:100px','class'=>'fixed unidad')); ?>
												</td>
												<td>
													<input type="hidden" class="rowIndex" value="{0}" />
													<div class="remove">Eliminar</div>
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

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',['id'=>'guardar']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
$(document).ready(function(e){
	$(document).on('click','.tipo_camion',function(e){
		var i = $(this).attr('i');
		$('.camiones_propios'+i).val("");
		$('.camiones_arrendados'+i).val("");
		var tipo = $(this).val();
		$(this).attr('checked','checked');
		if(tipo == "propios"){
			$("#tipo_camionarrendado"+i).removeAttr('checked');
			$('.camiones_propios'+i).show();
			$('.camiones_arrendados'+i).hide();
			$('.camiones_arrendados'+i).val("");
		}
		else{
			$("#tipo_camionpropio"+i).removeAttr('checked');
			$('.camiones_propios'+i).hide();
			$('.camiones_propios'+i).val("");
			$('.camiones_arrendados'+i).show();
		}
	});

	$(document).on('click','.tipo_equipo',function(e){
		var i = $(this).attr('i');
		$('.equipos_propios'+i).val("");
		$('.equipos_arrendados'+i).val("");
		$(this).attr('checked','checked');
		var tipo = $(this).val();
		if(tipo == "propios"){
			$("#tipo_equipoarrendado"+i).removeAttr('checked');
			$('.equipos_propios'+i).show();
			$('.equipos_arrendados'+i).hide();
		}
		else{
			$("#tipo_equipopropio"+i).removeAttr('checked');
			$('.equipos_propios'+i).hide();
			$('.equipos_arrendados'+i).show();
		}
	});

	$('.tipo_camion').each(function(e){
		var i = $(this).attr('i');
		var checked = $(this).attr('checked');
		var tipo = $(this).val();
		if(checked != "checked"){
			$('.camiones_'+tipo+i).hide();
		}
	});

	$('.tipo_equipo').each(function(e){
		var i = $(this).attr('i');
		var checked = $(this).attr('checked');
		var tipo = $(this).val();
		if(checked != "checked"){
			$('.equipos_'+tipo+i).hide();
		}
	});

	$('#guardar').click(function(e){
		var ok = true;
		$('.unidad').each(function(e){
			$(this).css('background','white');
			if($(this).val() == ""){
				$(this).css('background','pink');
				ok = false;
			}
		});

		$('.tipo_camion').each(function(e){
			var i = $(this).attr('i');
			var checked = $(this).attr('checked');
			var tipo = $(this).val();
			$(this).css('background','white');
			$("#tipo_camionpropio"+i).parent().css('background','#EBF3FD');
			$("#tipo_camionarrendado"+i).parent().css('background','#EBF3FD');
			if($("#tipo_camionpropio"+i).attr('checked') == undefined && $("#tipo_camionarrendado"+i).attr('checked') == undefined){
				$("#tipo_camionpropio"+i).parent().css('background','pink');
				$("#tipo_camionarrendado"+i).parent().css('background','pink');
				ok = false;
			}
			if(checked == "checked"){
				var seleccionado = $('.camiones_'+tipo+i).val();
				if(seleccionado == ''){
					$('.camiones_'+tipo+i).css('background','pink');
					ok = false;
				}
			}
		});

		$('.tipo_equipo').each(function(e){
			var i = $(this).attr('i');
			var checked = $(this).attr('checked');
			var tipo = $(this).val();
			$(this).css('background','white');
			$("#tipo_equipopropio"+i).parent().css('background','#EBF3FD');
			$("#tipo_equipoarrendado"+i).parent().css('background','#EBF3FD');
			if($("#tipo_equipopropio"+i).attr('checked') == undefined && $("#tipo_camionarrendado"+i).attr('checked') == undefined){
				$("#tipo_equipopropio"+i).parent().css('background','pink');
				$("#tipo_equipoarrendado"+i).parent().css('background','pink');
				ok = false;
			}
			if(checked == "checked"){
				var seleccionado = $('.equipos_'+tipo+i).val();
				if(seleccionado == ''){
					$('.equipos_'+tipo+i).css('background','pink');
					ok = false;
				}
			}
		});
		return ok;
	});
});
</script>