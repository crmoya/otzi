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
										echo $form->dropDownList($od,"[$i]origen",CHtml::listData(Origen::model()->listar(), 'id', 'nombre'),array('style'=>'width:100px'));  
										?>
									</td>
									<td>
										<?php 
										echo $form->dropDownList($od,"[$i]destino",CHtml::listData(Destino::model()->listar(), 'id', 'nombre'),array('style'=>'width:100px'));
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
										<div class="add">Agregar PU por distancia</div>
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


	<div class="complex">
		<table>
			<tr>
				<td style="vertical-align:top;">
					<div>
						<table class="templateFrame grid" cellspacing="0">
							<tbody class="templateTarget">
								<tr>
									<td>Cantidad</td>
									<td>Unidad</td>
									<td>PU</td>
									<td>&nbsp</td>
								</tr>
							<?php 
							if(isset($unidades)){	
								foreach($unidades as $i=>$u): ?>
								<tr class="templateContent">
									<td>
										<?php 
										echo $form->textField($u,"[$i]cantidad",array('style'=>'width:100px','class'=>'fixed0')); 
										?>
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
												<td width="100px">
												<?php echo CHtml::textField('Unidadfaena[{0}][cantidad]','',array('style'=>'width:100px','class'=>'fixed0')); ?>
												</td>
												<td width="100px">	
													<?php echo CHtml::dropDownList('Unidadfaena[{0}][unidad]','',CHtml::listData(Unidadfaena::listar(), 'id', 'nombre'),array('style'=>'width:100px')); ?>
												</td>
												<td width="100px">
													<?php echo CHtml::textField('Unidadfaena[{0}][pu]','',array('style'=>'width:100px','class'=>'fixed')); ?>
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
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->