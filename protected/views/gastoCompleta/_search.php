<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'retenido'); ?>
		<?php echo $form->textArea($model,'retenido',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cantidad'); ?>
		<?php echo $form->textArea($model,'cantidad',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'centro_costo_faena'); ?>
		<?php echo $form->textArea($model,'centro_costo_faena',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'departamento'); ?>
		<?php echo $form->textArea($model,'departamento',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'faena'); ?>
		<?php echo $form->textArea($model,'faena',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'impuesto_especifico'); ?>
		<?php echo $form->textArea($model,'impuesto_especifico',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iva'); ?>
		<?php echo $form->textArea($model,'iva',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'km_carguio'); ?>
		<?php echo $form->textArea($model,'km_carguio',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'litros_combustible'); ?>
		<?php echo $form->textArea($model,'litros_combustible',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'monto_neto'); ?>
		<?php echo $form->textArea($model,'monto_neto',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nombre_quien_rinde'); ?>
		<?php echo $form->textArea($model,'nombre_quien_rinde',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nro_documento'); ?>
		<?php echo $form->textArea($model,'nro_documento',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'periodo_planilla'); ?>
		<?php echo $form->textArea($model,'periodo_planilla',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rut_proveedor'); ?>
		<?php echo $form->textArea($model,'rut_proveedor',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'supervisor_combustible'); ?>
		<?php echo $form->textArea($model,'supervisor_combustible',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tipo_documento'); ?>
		<?php echo $form->textArea($model,'tipo_documento',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'unidad'); ?>
		<?php echo $form->textArea($model,'unidad',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehiculo_equipo'); ?>
		<?php echo $form->textArea($model,'vehiculo_equipo',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehiculo_oficina_central'); ?>
		<?php echo $form->textArea($model,'vehiculo_oficina_central',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gasto_id'); ?>
		<?php echo $form->textField($model,'gasto_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->