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
			var valid = checkNotNull(2);
			valid = valid && checkInstitucion();
			return valid;
		});

		$(".dinero3").each(function(e){
			var text = $(this).val();
			text = replaceAll(text,'.',',');
			var arr = text.split(",");
			if(arr.length > 2){
				$(this).val(0);
				return true;
			}
			else{
				var numero = arr[0];
				var decimal = 0;
				if(arr.length==2) decimal = arr[1];
				decimal = llenaConCeros(decimal);
				if(!isNaN(numero)){
					var num = new Number(numero);
					num = num.toFixed(0);
					num = formatThousands(num,'.');
					num = num + "," + decimal;
			   		$(this).val(num);
				}else{
					$(this).val(0);
				}
			}
		});
		
		
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Editar Garantías del Contrato <?php echo CHtml::encode($contrato->nombre);?></h3>

<?php if(Yii::app()->user->hasFlash('garantiasMessage')): ?>

<div class="flash-success">
<?php echo Yii::app()->user->getFlash('garantiasMessage'); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('garantiasError')): ?>

<div class="flash-error">
<?php echo Yii::app()->user->getFlash('garantiasError'); ?>
</div>
<?php endif; ?>

<?php if(!Yii::app()->user->hasFlash('garantiasMessage') && !Yii::app()->user->hasFlash('garantiasError')): ?>

<div class="form" style="width:900px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'editar-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>
	<div>
		<b>Fecha Inicio del Contrato:</b> <?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?>
	</div>
	<br/>
	
	<fieldset>
		<legend>Garantías del Contrato</legend>
		<?php 
		$iterator=0;
		foreach($garantias_asociadas as $i=>$iter):?>
		<table class="tableGarantias">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="4">
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
									<?php echo $form->textField($iter,"[$i]numero"); ?>
									<div id="error<?php echo CHtml::encode($iter->numero);?>" class="errorMessage" style="display:none;"></div>
							</td>
							<td>
									<b>Monto:</b>
									<?php echo $form->textField($iter,"[$i]monto",array('class'=>'dinero3','size'=>'14')); ?>
									<div id="error<?php echo CHtml::encode($iter->monto);?>" class="errorMessage" style="display:none;"></div>
							</td>
							<td> 	<b>Monto en:</b><?php //echo $form->labelEx($iter,"[$i]tipo_monto"); ?>
									<?php echo $form->dropDownList($iter,"[$i]tipo_monto", 
												CHtml::listData(Tools::getTiposMontos(), 'id', 'nombre')); ?>
							</td>
							<td rowspan="2">
									<b>Fecha de Vencimiento:</b>
							
							<div class="fecha_container">
								<?php
								$this->widget('zii.widgets.jui.CJuiDatePicker',
									array(
										'model'=>$iter,
										'language' => 'es',
										'attribute'=> "[$i]fecha_vencimiento",
										// additional javascript options for the date picker plugin
										'options'=>array(
											'showAnim'=>'fold',
											'dateFormat'=>'dd/mm/yy',
											'changeYear'=>true,
											'changeMonth'=>true,
										),
										'htmlOptions'=>array(
									        'style'=>'width:70px;',			
											'value'=> CHtml::encode(Tools::backFecha($iter->fecha_vencimiento)),
											'class'=>'fecha_vencimiento fecha',
											'nId'=>'0',
											'readonly'=>'readonly',
									    ),
									)
								);
								?></div>
								<div id="errorFecha0" class="errorMessage" style="display:none;"></div>
							</td>
							
							
						</tr>
						<tr>
							<td >
								<b>Institución asociada: </b>
									<?php
										echo $form->dropDownList(
											$iter,
											"[$i]instituciones_id",
											CHtml::listData(Instituciones::model()->listar(), 'id', 'nombre'),
											array('class'=>'institucion')
										);
										?> 
										<br/><div id="errorInstitucion" class="errorMessage" style="display:none;"></div>
							</td>
							<td>		
									<div><b>Tipo de garantía: </b>
									<?php 
										$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
											'name'=>"tipo_garantia[$i]",
											'value'=>TiposGarantias::model()->findByPk($iter->tipos_garantias_id)->nombre,
										    'source'=>$tipos_garantias,
										    // additional javascript options for the autocomplete plugin
										    'options'=>array('minLength'=>'1'),
										    'htmlOptions'=>array('class'=>'not_null1 upper')
										));
									?>
									<br/><div id="errorNotNull1" class="errorMessage" style="display:none;"></div>
									</div>
							</td>
							<td>	
									<div><b>Objeto de garantía: </b>
									<?php 
										$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
											'name'=>"objeto_garantia[$i]",
											'value'=>ObjetosGarantias::model()->findByPk($iter->objetos_garantias_id)->descripcion,
										    'source'=>$objetos_garantias,
										    // additional javascript options for the autocomplete plugin
										    'options'=>array('minLength'=>'1'),
										    'htmlOptions'=>array('class'=>'not_null0 upper')
										));	
									?>
									<br/><div id="errorNotNull0" class="errorMessage" style="display:none;"></div>
									</div>
							</td>
						</tr>
						<tr>
							<td>
								<b>Estado:</b>
								<?php echo $form->dropDownList($iter,"[$i]estado_garantia", CHtml::listData(Tools::getEstadosGarantias(), 'id', 'nombre')); ?>
							</td>
							<td colspan="3">
							<?php if(!$iter->estado_garantia):?>
								<b>Fecha Devolución:</b>
								<?php echo Tools::backFecha($iter->fecha_devolucion);?>
							<?php endif;?>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<b>Observación:</b><br>
									<?php echo $form->textArea($iter,"[$i]observacion",array('class'=>'upper','cols'=>'100','rows'=>'5','style'=>'overflow:auto;resize:none;','value'=>Tools::ponePorcentaje($iter->observacion))); ?>
									<div id="error<?php echo CHtml::encode($iter->id);?>" class="errorMessage" style="display:none;"></div>
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
		

	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


