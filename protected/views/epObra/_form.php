<?php
/* @var $this EpObraController */
/* @var $model EpObra */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ep-obra-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'produccion'); ?>
		<?php echo $form->textField($model,'produccion'); ?>
		<?php echo $form->error($model,'produccion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'costo'); ?>
		<?php echo $form->textField($model,'costo'); ?>
		<?php echo $form->error($model,'costo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reajuste'); ?>
		<?php echo $form->textField($model,'reajuste'); ?>
		<?php echo $form->error($model,'reajuste'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'retencion'); ?>
		<?php echo $form->textField($model,'retencion'); ?>
		<?php echo $form->error($model,'retencion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descuento'); ?>
		<?php echo $form->textField($model,'descuento'); ?>
		<?php echo $form->error($model,'descuento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mes'); ?>
		<?php echo $form->textField($model,'mes'); ?>
		<?php echo $form->error($model,'mes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'agno'); ?>
		<?php echo $form->textField($model,'agno'); ?>
		<?php echo $form->error($model,'agno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comentarios'); ?>
		<?php echo $form->textField($model,'comentarios',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'comentarios'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'resoluciones_id'); ?>
		<?php echo $form->textField($model,'resoluciones_id'); ?>
		<?php echo $form->error($model,'resoluciones_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->