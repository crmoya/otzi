<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gasto-completa-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'retenido'); ?>
		<?php echo $form->textArea($model,'retenido',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'retenido'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cantidad'); ?>
		<?php echo $form->textArea($model,'cantidad',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'cantidad'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'centro_costo_faena'); ?>
		<?php echo $form->textArea($model,'centro_costo_faena',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'centro_costo_faena'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'departamento'); ?>
		<?php echo $form->textArea($model,'departamento',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'departamento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'faena'); ?>
		<?php echo $form->textArea($model,'faena',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'faena'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'impuesto_especifico'); ?>
		<?php echo $form->textArea($model,'impuesto_especifico',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'impuesto_especifico'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iva'); ?>
		<?php echo $form->textArea($model,'iva',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'iva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'km_carguio'); ?>
		<?php echo $form->textArea($model,'km_carguio',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'km_carguio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'litros_combustible'); ?>
		<?php echo $form->textArea($model,'litros_combustible',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'litros_combustible'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'monto_neto'); ?>
		<?php echo $form->textArea($model,'monto_neto',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'monto_neto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre_quien_rinde'); ?>
		<?php echo $form->textArea($model,'nombre_quien_rinde',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'nombre_quien_rinde'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nro_documento'); ?>
		<?php echo $form->textArea($model,'nro_documento',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'nro_documento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'periodo_planilla'); ?>
		<?php echo $form->textArea($model,'periodo_planilla',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'periodo_planilla'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rut_proveedor'); ?>
		<?php echo $form->textArea($model,'rut_proveedor',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'rut_proveedor'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'supervisor_combustible'); ?>
		<?php echo $form->textArea($model,'supervisor_combustible',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'supervisor_combustible'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tipo_documento'); ?>
		<?php echo $form->textArea($model,'tipo_documento',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tipo_documento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unidad'); ?>
		<?php echo $form->textArea($model,'unidad',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'unidad'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vehiculo_equipo'); ?>
		<?php echo $form->textArea($model,'vehiculo_equipo',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'vehiculo_equipo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vehiculo_oficina_central'); ?>
		<?php echo $form->textArea($model,'vehiculo_oficina_central',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'vehiculo_oficina_central'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gasto_id'); ?>
		<?php echo $form->textField($model,'gasto_id'); ?>
		<?php echo $form->error($model,'gasto_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->