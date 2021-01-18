<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route,['policy'=>$policy]),
	'method'=>'get',
)); 


?>

<table>
 <tr>
  <td>
  	<?php echo $form->label($model,'igual'); ?>
  </td>
  <td>
  	<?php echo $form->dropDownList($model,'igual', CHtml::listData(array(array('id'=>'TODOS','nombre'=>'TODOS'),array('id'=>'SIN ERRORES','nombre'=>'SIN ERRORES'),array('id'=>'CON ERRORES','nombre'=>'CON ERRORES')), 'id', 'nombre')); ?>
  </td>
  <td>
  	<?php echo $form->label($model,'fecha_inicio'); ?>
  </td>
  <td>
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
					'style'=>'width:90px;',
					'readonly'=>'readonly',
				),
			)
		);
	?>
	</td>
	<td>
  	<?php echo $form->label($model,'fecha_fin'); ?>
  </td>
  <td>
  	<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker',
			array(
				'model'=>$model,
				'language' => 'es',
				'attribute'=>'fecha_fin',
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
	?>
	</td>
 </tr>
 <tr>
  <td colspan="2">
    <div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar',['class'=>'btn btn-primary']); ?>
	</div>
  </td>
 </tr>
</table>
<?php $this->endWidget(); ?>

</div><!-- search-form -->