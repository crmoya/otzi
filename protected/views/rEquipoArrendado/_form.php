<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'requipo-arrendado-form',
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
		<?php echo $form->textField($model,'reporte'); ?>
		<?php echo $form->error($model,'reporte'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ordenCompra'); ?>
		<?php echo $form->textField($model,'ordenCompra',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'ordenCompra'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'observaciones'); ?>
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'observaciones'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hInicial'); ?>
		<?php echo $form->textField($model,'hInicial',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'hInicial'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hFinal'); ?>
		<?php echo $form->textField($model,'hFinal',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'hFinal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'horas'); ?>
		<?php echo $form->textField($model,'horas',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'horas'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'faena_id'); ?>
		<?php echo $form->textField($model,'faena_id'); ?>
		<?php echo $form->error($model,'faena_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'equipoArrendado_id'); ?>
		<?php echo $form->textField($model,'equipoArrendado_id'); ?>
		<?php echo $form->error($model,'equipoArrendado_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'operador_id'); ?>
		<?php echo $form->textField($model,'operador_id'); ?>
		<?php echo $form->error($model,'operador_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->