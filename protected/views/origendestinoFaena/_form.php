<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'origendestino-faena-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'origen_id'); ?>
		<?php echo $form->dropDownList($model,'origen_id', CHtml::listData(Origen::model()->listar(), 'id', 'nombre')); ?>
	  	<?php echo $form->error($model,'origen_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'destino_id'); ?>
		<?php echo $form->dropDownList($model,'destino_id', CHtml::listData(Destino::model()->listar(), 'id', 'nombre')); ?>
		<?php echo $form->error($model,'destino_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'faena_id'); ?>
		<?php echo $form->dropDownList($model,'faena_id', CHtml::listData(Faena::model()->listar(), 'id', 'nombre')); ?>
		<?php echo $form->error($model,'faena_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pu'); ?>
		<?php echo $form->textField($model,'pu',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'pu'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->