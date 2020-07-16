<div class="wide form">

<?php 
$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>


<table>
 <tr>
  <td>
  	<div class="row">
  		<?php echo $form->label($model,'fechaInicio'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',
				array(
					'model'=>$model,
					'language' => 'es',
					'attribute'=>'fechaInicio',
					// additional javascript options for the date picker plugin
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'dd/mm/yy',
						'changeYear'=>true,
						'changeMonth'=>true,
					),
					'htmlOptions'=>array(
				        'style'=>'width:70px;',
						'readonly'=>'readonly',
				    ),
				)
			);
		?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'fechaFin'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',
				array(
					'model'=>$model,
					'language' => 'es',
					'attribute'=>'fechaFin',
					// additional javascript options for the date picker plugin
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'dd/mm/yy',
						'changeYear'=>true,
						'changeMonth'=>true,
					),
					'htmlOptions'=>array(
				        'style'=>'width:70px;',
						'readonly'=>'readonly',
				    ),
				)
			);
		?>
	</div>
  </td>
 </tr>
 <tr>
  <td colspan="2">
    <div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar'); ?>
	</div>
  </td>
 </tr>
</table>
	

	
<?php $this->endWidget(); ?>

</div><!-- search-form -->