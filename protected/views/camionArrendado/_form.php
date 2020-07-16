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
	'id'=>'camion-arrendado-form',
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
		<?php echo $form->labelEx($model,'capacidad'); ?>
		<?php echo $form->textField($model,'capacidad',array('size'=>10,'maxlength'=>10,'class'=>'fixed')); ?>
		<?php echo $form->error($model,'capacidad'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pesoOVolumen'); ?>
	    <?php echo $form->dropDownList($model,'pesoOVolumen', CHtml::listData(array(array('id'=>'P','nombre'=>'Kgs'),array('id'=>'V','nombre'=>'M3'),array('id'=>'L','nombre'=>'Lt')), 'id', 'nombre')); ?>
	  	<?php echo $form->error($model,'pesoOVolumen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'consumoPromedio'); ?>
		<?php echo $form->textField($model,'consumoPromedio',array('size'=>10,'maxlength'=>10,'class'=>'fixed')); ?>
		<?php echo $form->error($model,'consumoPromedio'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'produccionMinima'); ?>
		<?php echo $form->textField($model,'produccionMinima',array('size'=>10,'maxlength'=>10,'class'=>'fixed')); ?>
		<?php echo $form->error($model,'produccionMinima'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'horasMin'); ?>
		<?php echo $form->textField($model,'horasMin',array('size'=>10,'maxlength'=>10,'class'=>'fixed')); ?>
		<?php echo $form->error($model,'horasMin'); ?>
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