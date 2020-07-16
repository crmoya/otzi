<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'informe-reg-exp-equipo-propio-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
		<?php echo $form->error($model,'fecha'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reporte'); ?>
		<?php echo $form->textField($model,'reporte',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'reporte'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'observaciones'); ?>
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'observaciones'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'equipo'); ?>
		<?php echo $form->textField($model,'equipo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'equipo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'codigo'); ?>
		<?php echo $form->textField($model,'codigo',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'codigo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'horasReales'); ?>
		<?php echo $form->textField($model,'horasReales',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'horasReales'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'combustible'); ?>
		<?php echo $form->textField($model,'combustible',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'combustible'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'repuesto'); ?>
		<?php echo $form->textField($model,'repuesto'); ?>
		<?php echo $form->error($model,'repuesto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'horasPanne'); ?>
		<?php echo $form->textField($model,'horasPanne',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'horasPanne'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->