<?php
/* @var $this ExpedicionesController */
/* @var $model Expediciones */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'expediciones-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nVueltas'); ?>
		<?php echo $form->textField($model,'nVueltas'); ?>
		<?php echo $form->error($model,'nVueltas'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'totalTransportado'); ?>
		<?php echo $form->textField($model,'totalTransportado',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'totalTransportado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kmRecorridos'); ?>
		<?php echo $form->textField($model,'kmRecorridos',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'kmRecorridos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
		<?php echo $form->error($model,'fecha'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vehiculo'); ?>
		<?php echo $form->textField($model,'vehiculo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'vehiculo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'chofer'); ?>
		<?php echo $form->textField($model,'chofer',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'chofer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'propio_arrendado'); ?>
		<?php echo $form->textField($model,'propio_arrendado',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'propio_arrendado'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->