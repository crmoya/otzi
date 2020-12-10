<?php 

$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-1.7.2.min.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 

?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipo-propio-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'codigo'); ?>
		<?php echo $form->textField($model,'codigo',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'codigo'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'horasMin'); ?>
		<?php echo $form->textField($model,'horasMin',array('size'=>11,'maxlength'=>10,'class'=>'fixed')); ?>
		<?php echo $form->error($model,'horasMin'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'consumoEsperado'); ?>
		<?php echo $form->textField($model,'consumoEsperado',array('size'=>10,'maxlength'=>10,'class'=>'fixed')); ?>
		<?php echo $form->error($model,'consumoEsperado'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'valorHora'); ?>
		<?php echo $form->textField($model,'valorHora',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'valorHora'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'coeficienteDeTrato'); ?>
		<?php echo $form->textField($model,'coeficienteDeTrato',array('size'=>10,'maxlength'=>10,'class'=>'fixed')); ?>
		<?php echo $form->error($model,'coeficienteDeTrato'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'vigente'); ?>
	    <?php echo $form->dropDownList($model,'vigente', CHtml::listData(array(array('id'=>'SÍ','nombre'=>'SÍ'),array('id'=>'NO','nombre'=>'NO')), 'id', 'nombre')); ?>
	  	<?php echo $form->error($model,'vigente'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->