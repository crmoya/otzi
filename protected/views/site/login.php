<h1>Login</h1>

<p>Por favor ingrese su usuario/clave para acceder a la aplicación:</p>

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
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',['class'=>'form-control form-login']); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',['class'=>'form-control form-login']); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login',['class'=>'form-control form-login']); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<style>
.form-login{
	width: 400px;
}
</style>