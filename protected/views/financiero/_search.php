<?php
/* @var $this FinancieroController */
/* @var $model Financiero */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<fieldset>
		<legend>Filtro de mes</legend>
		<div class="row">
		<?php echo $form->label($model,'desde_mes'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'desde_mes',
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'dd/mm/yy',
							'changeYear'=>true,
							'changeMonth'=>true,
						),
						'htmlOptions'=>array(
					        'style'=>'width:70px;',
							//'readonly'=>'readonly',
					    ),
					)
				);
			?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'hasta_mes'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'hasta_mes',
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'dd/mm/yy',
							'changeYear'=>true,
							'changeMonth'=>true,
						),
						'htmlOptions'=>array(
					        'style'=>'width:70px;',
							//'readonly'=>'readonly',
					    ),
					)
				);
			?>
	</div>
	</fieldset>
	<fieldset>
		<legend>Agrupar por</legend>
	<div class="row">
		<?php echo $form->radioButtonList($model,'agrupar_por',array('no'=>'No Agrupar','nombre'=>'Nombre contrato','mes'=>'Mes'), array('style'=>'clear:left','onchange' => 'menuTypeChange(this.value);')); ?>
	</div>
	</fieldset>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->