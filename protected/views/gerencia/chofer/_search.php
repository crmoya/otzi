<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<table>
   	<tr>
   		<td><?php echo $form->labelEx($model,'agruparPor',array('style'=>'width:200px;')); ?>
   			<?php echo $form->checkbox($model,'agruparPor'); ?></td>
   		<td><?php echo $form->label($model,'fechaInicio'); ?>
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
				        'style'=>'width:90px;',
						'readonly'=>'readonly',
				    ),
				)
			);
		?></td>
		<td><?php echo CHtml::submitButton('Filtrar',['class'=>'btn btn-primary']); ?></td>
	</tr>
	<tr>
	 	<td><?php echo $form->labelEx($model,'propiosOArrendados'); ?>
	    <?php echo $form->dropDownList($model,'propiosOArrendados', CHtml::listData(array(array('id'=>'TODOS','nombre'=>'Propios y Arrendados'),array('id'=>'PROPIOS','nombre'=>'Solamente Propios'),array('id'=>'ARRENDADOS','nombre'=>'Solamente Arrendados')), 'id', 'nombre')); ?></td>
	    <td><?php echo $form->label($model,'fechaFin'); ?>
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
				        'style'=>'width:90px;',
						'readonly'=>'readonly',
				    ),
				)
			);
		?></td>
	</tr>
</table>

	

	
<?php $this->endWidget(); ?>

</div><!-- search-form -->