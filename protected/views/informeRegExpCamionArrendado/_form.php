<?php
/* @var $this InformeregexpcamionarrendadoController */
/* @var $model Informeregexpcamionarrendado */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'informeregexpcamionarrendado-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
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
		<?php echo $form->labelEx($model,'camion'); ?>
		<?php echo $form->textField($model,'camion',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'camion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kmRecorridos'); ?>
		<?php echo $form->textField($model,'kmRecorridos',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'kmRecorridos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kmGps'); ?>
		<?php echo $form->textField($model,'kmGps',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'kmGps'); ?>
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
		<?php echo $form->labelEx($model,'produccionReal'); ?>
		<?php echo $form->textField($model,'produccionReal',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'produccionReal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'horasPanne'); ?>
		<?php echo $form->textField($model,'horasPanne',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'horasPanne'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_reg'); ?>
		<?php echo $form->textField($model,'id_reg'); ?>
		<?php echo $form->error($model,'id_reg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'panne'); ?>
		<?php echo $form->textField($model,'panne',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'panne'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'validado'); ?>
		<?php echo $form->textField($model,'validado'); ?>
		<?php echo $form->error($model,'validado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'validador_id'); ?>
		<?php echo $form->textField($model,'validador_id'); ?>
		<?php echo $form->error($model,'validador_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->