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
		<?php echo $form->label($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'reporte'); ?>
		<?php echo $form->textField($model,'reporte'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ordenCompra'); ?>
		<?php echo $form->textField($model,'ordenCompra',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'observaciones'); ?>
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hInicial'); ?>
		<?php echo $form->textField($model,'hInicial',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hFinal'); ?>
		<?php echo $form->textField($model,'hFinal',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'horas'); ?>
		<?php echo $form->textField($model,'horas',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'faena_id'); ?>
		<?php echo $form->textField($model,'faena_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'equipoArrendado_id'); ?>
		<?php echo $form->textField($model,'equipoArrendado_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operador_id'); ?>
		<?php echo $form->textField($model,'operador_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->