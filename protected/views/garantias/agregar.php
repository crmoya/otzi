<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.currency.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 


?>
<script type="text/javascript">
	$(function() {
		$("#guardar").click(function(){
			var valid = checkNotNull(2);
			valid = valid && checkInstitucion();
			return valid;
		});
		
	});
</script>


<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Agregar nueva Garantía a <?php echo CHtml::encode($contrato->nombre);?></h3>

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
	'id'=>'agregar-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>
	<div>
		<b>Fecha Inicio del Contrato:</b> <?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?>
	</div>
	<br/>
	
	<fieldset id="fs">
		<legend>Agregar nueva Garantía</legend>
		<table>
			<tr>
				<td><?php echo $form->labelEx($garantia,'numero'); ?></td>
				<td><?php echo $form->textField($garantia,'numero',array('class'=>'none upper')); ?>
					<?php echo $form->error($garantia,'numero'); ?></td>
				<td><?php echo $form->labelEx($garantia,'monto'); ?>
					<?php echo $form->textField($garantia,'monto',array('class'=>'dinero3')); ?>
					<?php echo $form->error($garantia,'monto'); ?></td>
				<td><?php echo $form->labelEx($garantia,'tipo_monto'); ?>
				<?php	echo $form->dropDownList($garantia,'tipo_monto', 
							CHtml::listData(Tools::getTiposMontos(), 'id', 'nombre'));			
				?></td>
			</tr>
				<td>
					<?php echo $form->labelEx($garantia,'fecha_vencimiento'); ?>
				</td>
				<td class="fecha_container">
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',
						array(
							'model'=>$garantia,
							'language' => 'es',
							'attribute'=>'fecha_vencimiento',
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
								'class'=>'fecha_vencimiento fecha',
								'nId'=>'0',
								'readonly'=>'readonly',
						    ),
						)
					);
					?>
					<br/><div id="errorFecha0" class="errorMessage" style="display:none;"></div>
				</td>
				<td><?php echo $form->labelEx($garantia,'instituciones_id'); ?></td>
				<td><?php
					echo $form->dropDownList(
						$garantia,
						'instituciones_id',
						CHtml::listData(Instituciones::model()->listar(), 'id', 'nombre'),
						array('class'=>'institucion')
					);
					?> 
					<br/><div id="errorInstitucion" class="errorMessage" style="display:none;"></div>
				</td>
			</tr>
			<tr>
							<td><?php echo $form->labelEx($garantia,'objetos_garantias_id'); ?></td>
				<td><?php 
						$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
							'name'=>'objeto_garantia',
						    'source'=>$objetos_garantias,
						    // additional javascript options for the autocomplete plugin
						    'options'=>array(
						        'minLength'=>'1',
						    ),
						    'htmlOptions'=>array(
						    	'class'=>'not_null0 upper',
						    )
						));
					?>
					<br/><div id="errorNotNull0" class="errorMessage" style="display:none;"></div></td>				
			
				<td><?php echo $form->labelEx($garantia,'tipos_garantias_id')." (Póliza, Boleta, etc)"; ?></td>
				<td><?php 
						$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
							'name'=>'tipo_garantia',
						    'source'=>$tipos_garantias,
						    // additional javascript options for the autocomplete plugin
						    'options'=>array(
						        'minLength'=>'1',
						    ),
						    'htmlOptions'=>array(
						    	'class'=>'not_null1 upper',
						    )
						));
					?>
					<br/><div id="errorNotNull1" class="errorMessage" style="display:none;"></div>				
				</td>
				</tr>
				<tr>
				  <td><?php echo $form->labelEx($garantia,'observacion'); ?></td>
				  <td colspan="3"><?php echo $form->textArea($garantia,'observacion',array('class'=>'upper','cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?>
				</tr>
		</table>
	</fieldset>
	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>
	
	<fieldset>
		<legend>Garantías existentes en el Contrato</legend>
		<?php 
		if ($garantias_asociadas == null):
		?>
			<p class="note">El contrato actualmente no presenta Garantías ingresadas.</p>
		<?php  
		else:
		foreach($garantias_asociadas as $iter): 
		?>
		<table class="tableResolucion">
			<tr>
				<td>
					<div class="divResolucion">
					<table>
					<tr>
					<td>
						<b>N°Garantia:</b>
						<?php echo CHtml::encode($iter->numero); ?>
					</td>
					<td>
						<b>Monto:</b>
						<span>
							<?php 	echo "<b>".CHtml::encode($iter->tipo_monto)."</b>&nbsp;";
									echo "<span class='plata_decimales_sin'>".CHtml::encode($iter->monto)."</span>";
									 ?></span>
					
					</td>
					<td>
						<b>Fecha de Vencimiento:</b>
						<?php echo CHtml::encode(Tools::backFecha($iter->fecha_vencimiento)); ?>
						<input type="hidden" value="<?php echo CHtml::encode(Tools::backFecha($iter->fecha_vencimiento)); ?>" class="fecha_min"/>
					</td>
					</tr>
					<tr>
					<td>
						<b>Institución asociada: </b>
						<?php echo CHtml::encode(Instituciones::model()->findByPk($iter->instituciones_id)->nombre); ?>
					</td>
					<td>
						<b>Tipo de garantía: </b>
						<?php echo CHtml::encode(TiposGarantias::model()->findByPk($iter->tipos_garantias_id)->nombre); ?>
					</td>
					<td>
						<b>Objeto de garantía: </b>
						<?php echo CHtml::encode(ObjetosGarantias::model()->findByPk($iter->objetos_garantias_id)->descripcion); ?>
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
					</div>
				</td>
			</tr>
		</table>
		<?php 

		endforeach;
		endif;
		?>
	</fieldset>
		
	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


