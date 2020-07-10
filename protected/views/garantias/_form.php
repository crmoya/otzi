<?php
/* @var $this GarantiasController */
/* @var $model Garantias */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'garantias-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'numero'); ?>
		<?php echo $form->textField($model,'numero',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'numero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'monto'); ?>
		<?php echo $form->textField($model,'monto',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'monto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha_vencimiento'); ?>
		<?php echo $form->textField($model,'fecha_vencimiento'); ?>
		<?php echo $form->error($model,'fecha_vencimiento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'instituciones_id'); ?>
		<?php echo $form->textField($model,'instituciones_id'); ?>
		<?php echo $form->error($model,'instituciones_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tipos_garantias_id'); ?>
		<?php echo $form->textField($model,'tipos_garantias_id'); ?>
		<?php echo $form->error($model,'tipos_garantias_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contratos_id'); ?>
		<?php echo $form->textField($model,'contratos_id'); ?>
		<?php echo $form->error($model,'contratos_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'objetos_garantias_id'); ?>
		<?php echo $form->textField($model,'objetos_garantias_id'); ?>
		<?php echo $form->error($model,'objetos_garantias_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modificador_id'); ?>
		<?php echo $form->textField($model,'modificador_id'); ?>
		<?php echo $form->error($model,'modificador_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'creador_id'); ?>
		<?php echo $form->textField($model,'creador_id'); ?>
		<?php echo $form->error($model,'creador_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'observacion'); ?>
		<?php echo $form->textArea($model,'observacion',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'observacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tipo_monto'); ?>
		<?php echo $form->textField($model,'tipo_monto',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'tipo_monto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estado_garantia'); ?>
		<?php echo $form->textField($model,'estado_garantia'); ?>
		<?php echo $form->error($model,'estado_garantia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha_devolucion'); ?>
		<?php echo $form->textField($model,'fecha_devolucion'); ?>
		<?php echo $form->error($model,'fecha_devolucion'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->