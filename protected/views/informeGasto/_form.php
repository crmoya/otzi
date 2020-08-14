<?php
/* @var $this InformeGastoController */
/* @var $model InformeGasto */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'informe-gasto-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'titulo'); ?>
		<?php echo $form->textArea($model,'titulo',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'titulo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'numero'); ?>
		<?php echo $form->textField($model,'numero'); ?>
		<?php echo $form->error($model,'numero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha_envio'); ?>
		<?php echo $form->textField($model,'fecha_envio',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'fecha_envio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha_cierre'); ?>
		<?php echo $form->textField($model,'fecha_cierre',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'fecha_cierre'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre_empleado'); ?>
		<?php echo $form->textField($model,'nombre_empleado',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'nombre_empleado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rut_empleado'); ?>
		<?php echo $form->textField($model,'rut_empleado',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'rut_empleado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aprobado_por'); ?>
		<?php echo $form->textField($model,'aprobado_por',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'aprobado_por'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'politica_id'); ?>
		<?php echo $form->textField($model,'politica_id'); ?>
		<?php echo $form->error($model,'politica_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'politica'); ?>
		<?php echo $form->textField($model,'politica',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'politica'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estado'); ?>
		<?php echo $form->textField($model,'estado'); ?>
		<?php echo $form->error($model,'estado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total'); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_aprobado'); ?>
		<?php echo $form->textField($model,'total_aprobado'); ?>
		<?php echo $form->error($model,'total_aprobado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nro_gastos'); ?>
		<?php echo $form->textField($model,'nro_gastos'); ?>
		<?php echo $form->error($model,'nro_gastos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nro_gastos_aprobados'); ?>
		<?php echo $form->textField($model,'nro_gastos_aprobados'); ?>
		<?php echo $form->error($model,'nro_gastos_aprobados'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nro_gastos_rechazados'); ?>
		<?php echo $form->textField($model,'nro_gastos_rechazados'); ?>
		<?php echo $form->error($model,'nro_gastos_rechazados'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->