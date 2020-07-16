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
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'propietario'); ?>
		<?php echo $form->textField($model,'propietario'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'horasMin'); ?>
		<?php echo $form->textField($model,'horasMin'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'precioUnitario'); ?>
		<?php echo $form->textField($model,'precioUnitario'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'valorHora'); ?>
		<?php echo $form->textField($model,'valorHora'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'vigente'); ?>
	    <?php echo $form->dropDownList($model,'vigente', CHtml::listData(array(array('id'=>'SÍ','nombre'=>'SÍ'),array('id'=>'NO','nombre'=>'NO')), 'id', 'nombre')); ?>
	  	<?php echo $form->error($model,'vigente'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->