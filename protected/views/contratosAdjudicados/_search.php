<?php
/* @var $this ContratosAdjudicadosController */
/* @var $model ContratosAdjudicados */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
<fieldset>
	<legend>Todos los proyectos iniciados entre:</legend>
	<div class="row">
		<?php echo $form->label($model,'inicio_desde'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'inicio_desde',
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
		<?php echo $form->label($model,'inicio_hasta'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'inicio_hasta',
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
	<legend>Todos los proyectos terminados entre:</legend>
	<div class="row">
		<?php echo $form->label($model,'termino_desde'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'termino_desde',
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
		<?php echo $form->label($model,'termino_hasta'); ?>
		<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'termino_hasta',
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