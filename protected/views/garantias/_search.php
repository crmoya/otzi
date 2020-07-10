<?php
/* @var $this GarantiasController */
/* @var $model Garantias */
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
		<?php echo $form->label($model,'numero'); ?>
		<?php echo $form->textField($model,'numero',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'monto'); ?>
		<?php echo $form->textField($model,'monto',array('size'=>13,'maxlength'=>13)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_vencimiento'); ?>
		<?php echo $form->textField($model,'fecha_vencimiento'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'instituciones_id'); ?>
		<?php echo $form->textField($model,'instituciones_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tipos_garantias_id'); ?>
		<?php echo $form->textField($model,'tipos_garantias_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contratos_id'); ?>
		<?php echo $form->textField($model,'contratos_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'objetos_garantias_id'); ?>
		<?php echo $form->textField($model,'objetos_garantias_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modificador_id'); ?>
		<?php echo $form->textField($model,'modificador_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'creador_id'); ?>
		<?php echo $form->textField($model,'creador_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'observacion'); ?>
		<?php echo $form->textArea($model,'observacion',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tipo_monto'); ?>
		<?php echo $form->textField($model,'tipo_monto',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estado_garantia'); ?>
		<?php echo $form->textField($model,'estado_garantia'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_devolucion'); ?>
		<?php echo $form->textField($model,'fecha_devolucion'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->