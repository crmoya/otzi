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
	    <?php echo $form->dropDownList($model,'agruparPor', CHtml::listData(array(array('id'=>'NINGUNO','nombre'=>'Sin agrupación'),array('id'=>'CAMION','nombre'=>'camión, camioneta, auto'),array('id'=>'CHOFER','nombre'=>'Chofer'),array('id'=>'CENTROGESTION','nombre'=>'Centro de Gestión'),array('id'=>'CENTROCAMION','nombre'=>'Centro de Gestión y camión, camioneta, auto'),array('id'=>'CENTROCHOFER','nombre'=>'Centro de Gestión y Chofer'),array('id'=>'CHOFERCAMION','nombre'=>'Chofer y camión, camioneta, auto')), 'id', 'nombre')); ?>
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