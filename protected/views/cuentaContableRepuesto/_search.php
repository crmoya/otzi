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
		<?php echo $form->label($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar',['class'=>'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->