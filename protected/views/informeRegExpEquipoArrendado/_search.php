<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<table>
    <tr>
        <td><?php echo $form->label($model,'fechaInicio'); ?></td>
        <td>
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
        </td>
        <td><?php echo $form->labelEx($model,"equipo_id",array('style'=>'width:80px;'));?></td>
        <td><?php echo $form->dropDownList($model,'equipo_id',CHtml::listData(EquipoArrendado::model()->listar(), 'id', 'nombre'));?></td>
    </tr>
    <tr>
        <td><?php echo $form->label($model,'fechaFin'); ?></td>
        <td><?php 
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
        </td>	
	<td><?php echo $form->labelEx($model,"faena_id",array('style'=>'width:80px;'));?></td>
        <td><?php echo $form->dropDownList($model,'faena_id',CHtml::listData(Faena::model()->listar(), 'id', 'nombre'));?></td>		
  
 </tr>	
 <tr>
     <td>
        <?php echo $form->labelEx($model,"reporte",array('style'=>'width:80px;'));?>
    </td>
    <td>
        <?php echo $form->textField($model,'reporte');?>
    </td>
    <td><?php echo CHtml::submitButton('Filtrar'); ?></div>
  </td>
 </tr>
</table>
	

	
<?php $this->endWidget(); ?>

</div><!-- search-form -->