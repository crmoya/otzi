<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.currency.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 


?>
<script language="javascript" type="text/javascript">
	$(function() {
		$("#guardar").click(function(){
			var valid = true;
			valid = valid && checkFechas(1);
			return valid;
		});
			
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Creación de Nuevo Posible Contrato:</h3>

<?php if(Yii::app()->user->hasFlash('contratosMessage')): ?>

<div class="flash-success">
<?php echo Yii::app()->user->getFlash('contratosMessage'); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('contratosError')): ?>

<div class="flash-error">
<?php echo Yii::app()->user->getFlash('contratosError'); ?>
</div>
<?php endif; ?>

<?php if(!Yii::app()->user->hasFlash('contratosMessage') && !Yii::app()->user->hasFlash('contratosError')): ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contratos-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>

	<p class="note" id="note">
		Campos con <span class="required">*</span> son requeridos.
	</p>

	<fieldset id="fs">
		<legend>Datos del Contrato</legend>
		<table>
                        <tr>
				<td><?php echo $form->labelEx($model,'rut_mandante'); ?>
				</td>
				<td><?php echo $form->textField($model,'rut_mandante',array('size'=>12,'maxlength'=>12,'class'=>'upper'));?> <?php echo $form->error($model,'rut_mandante'); ?>
				</td>
			</tr>
                        <tr>
				<td><?php echo $form->labelEx($model,'nombre_mandante'); ?>
				</td>
				<td><?php echo $form->textField($model,'nombre_mandante',array('size'=>110,'maxlength'=>200,'class'=>'upper'));?> <?php echo $form->error($model,'nombre_mandante'); ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'nombre'); ?>
				</td>
				<td><?php echo $form->textField($model,'nombre',array('size'=>110,'maxlength'=>200,'class'=>'upper'));?> <?php echo $form->error($model,'nombre'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $form->labelEx($model,'fecha_inicio'); ?>
				</td>
				<td class="fecha_container">
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',
						array(
							'model'=>$model,
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
								'value'=>date("d/m/Y"),
								'class'=>'fecha_inicio fecha',
								'nId'=>'0',
						    ),
						)
					);
					?>
					<br/><div id="errorFecha0" class="errorMessage" style="display:none;"></div>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'tipos_contratos_id');?></td>
				<td><?php echo $form->dropDownList($model,'tipos_contratos_id', CHtml::listData(TiposContratos::model()->findAll(), 'id', 'nombre')); ?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'tipos_reajustes_id');?></td>
				<td><?php echo $form->dropDownList($model,'tipos_reajustes_id', CHtml::listData(TiposReajustes::model()->findAll(), 'id', 'nombre')); ?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'presupuesto_oficial');?></td>
				<td><?php echo $form->textField($model,'presupuesto_oficial',array('class'=>'dinero upper')); ?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'codigo_safi');?></td>
				<td><?php echo $form->textField($model,'codigo_safi',array('class'=>'upper')); ?></td>
			</tr>
		</table>
	</fieldset>
	
	<table cellspacing="0" cellpadding="0">

		<tr>
			<td width="30"><?php echo $form->labelEx($model,'observacion'); ?>
			</td>
			<td><?php echo $form->textArea($model,'observacion',array('class'=>'upper','cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?>
			<?php echo $form->error($model,'observacion'); ?></td>
		</tr>
	</table>


	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


