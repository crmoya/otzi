<?php
/* @var $this InformeGastoController */
/* @var $model InformeGasto */
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
		<?php echo $form->label($model,'titulo'); ?>
		<?php echo $form->textArea($model,'titulo',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'numero'); ?>
		<?php echo $form->textField($model,'numero'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_envio'); ?>
		<?php echo $form->textField($model,'fecha_envio',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_cierre'); ?>
		<?php echo $form->textField($model,'fecha_cierre',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nombre_empleado'); ?>
		<?php echo $form->textField($model,'nombre_empleado',array('size'=>60,'maxlength'=>300)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rut_empleado'); ?>
		<?php echo $form->textField($model,'rut_empleado',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aprobado_por'); ?>
		<?php echo $form->textField($model,'aprobado_por',array('size'=>60,'maxlength'=>300)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'politica_id'); ?>
		<?php echo $form->textField($model,'politica_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'politica'); ?>
		<?php echo $form->textField($model,'politica',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estado'); ?>
		<?php echo $form->textField($model,'estado'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'total'); ?>
		<?php echo $form->textField($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'total_aprobado'); ?>
		<?php echo $form->textField($model,'total_aprobado'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nro_gastos'); ?>
		<?php echo $form->textField($model,'nro_gastos'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nro_gastos_aprobados'); ?>
		<?php echo $form->textField($model,'nro_gastos_aprobados'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nro_gastos_rechazados'); ?>
		<?php echo $form->textField($model,'nro_gastos_rechazados'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->