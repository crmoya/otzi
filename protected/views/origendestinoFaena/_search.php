<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'origen_id'); ?>
		<?php echo $form->textField($model,'origen_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'destino_id'); ?>
		<?php echo $form->textField($model,'destino_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'faena_id'); ?>
		<?php echo $form->textField($model,'faena_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pu'); ?>
		<?php echo $form->textField($model,'pu',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->