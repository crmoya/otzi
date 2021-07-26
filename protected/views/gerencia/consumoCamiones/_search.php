<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<table>
 <tr>
  <td>
   <div class="row">
		<?php echo $form->labelEx($model,'propiosOArrendados'); ?>
	    <?php echo $form->dropDownList($model,'propiosOArrendados', CHtml::listData(array(array('id'=>'TODOS','nombre'=>'Propios y Arrendados'),array('id'=>'PROPIOS','nombre'=>'Solamente Propios'),array('id'=>'ARRENDADOS','nombre'=>'Solamente Arrendados')), 'id', 'nombre')); ?>
   </div>
   <div class="row">
		<?php echo $form->labelEx($model,'agruparPor'); ?>
	    <?php echo $form->dropDownList($model,'agruparPor', CHtml::listData(array(array('id'=>'NINGUNO','nombre'=>'Sin agrupación'),array('id'=>'MAQUINA','nombre'=>'camión, camioneta, auto'),array('id'=>'OPERADOR','nombre'=>'Chofer')), 'id', 'nombre')); ?>
   </div>
   <div class="row">
   		<?php echo $form->labelEx($model,"tipoCombustible_id",array('style'=>'width:80px;'));?>
		<?php echo $form->dropDownList($model,'tipoCombustible_id',CHtml::listData(TipoCombustible::model()->listar(), 'id', 'nombre'));?>
   </div>
  </td>
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
				        'style'=>'width:90px;',
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
				        'style'=>'width:90px;',
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
		<?php echo CHtml::submitButton('Filtrar',['class'=>'btn btn-primary']); ?>
	</div>
  </td>
 </tr>
</table>
	

	
<?php $this->endWidget(); ?>

</div><!-- search-form -->