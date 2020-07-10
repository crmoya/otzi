<?php
/* @var $this InformeGarantiasController */
/* @var $model InformeGarantias */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'institucion'); ?>
		<?php echo $form->textField($model,'institucion',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contrato'); ?>
		<?php echo $form->textField($model,'contrato',array('size'=>60,'maxlength'=>200)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'estado'); ?>
		<?php echo $form->dropDownList($model,"estado", CHtml::listData(array(array('id'=>'Vigente','nombre'=>'Vigente'),array('id'=>'No Vigente','nombre'=>'No Vigente')), 'id', 'nombre')); ?>
	</div>

	<fieldset>
		<legend>Filtro de Fecha de vencimiento</legend>
	<div class="row">
		<?php echo $form->label($model,'desde_fecha'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'desde_fecha',
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
		<?php echo $form->label($model,'hasta_fecha'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'hasta_fecha',
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
		<legend>Filtro de Fecha de devolución</legend>
	<div class="row">
		<?php echo $form->label($model,'desde_fecha_d'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'desde_fecha_d',
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
		<?php echo $form->label($model,'hasta_fecha_d'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'hasta_fecha_d',
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

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->