<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'destino-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class="row">
		<?php echo $form->labelEx($model,'sigla'); ?>
		<?php echo $form->textField($model,'sigla',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'sigla'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->