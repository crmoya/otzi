<p>Por favor ingrese sus datos para el cambio de clave:</p>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>

<?php else: ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'clave'); ?>
		<?php echo $form->passwordField($model,'clave'); ?>
		<?php echo $form->error($model,'clave'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nueva'); ?>
		<?php echo $form->passwordField($model,'nueva'); ?>
		<?php echo $form->error($model,'nueva'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'repita'); ?>
		<?php echo $form->passwordField($model,'repita'); ?>
		<?php echo $form->error($model,'repita'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Cambiar Clave',['class'=>'btn btn-primary form-control']); ?>
	</div>

<?php $this->endWidget(); 
endif;?>

</div><!-- form -->
