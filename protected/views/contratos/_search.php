<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<table>
		<tr>
			<td>
			<?php echo $form->label($model,'fecha_inicio'); ?>
			<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'fecha_inicio',
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
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $form->label($model,'id'); ?>
				<?php echo $form->textField($model,'id'); ?>
			</td>
			<td>
				<?php echo $form->label($model,'nombre'); ?>
				<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>100)); ?>
			</td>
		</tr>
	</table>	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Buscar'); ?>
	</div>

<?php $this->endWidget();?>

</div><!-- search-form -->