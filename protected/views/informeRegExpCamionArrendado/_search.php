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
                        'style'=>'width:90px;',
                                'readonly'=>'readonly',
                        ),
                    )
                );
            ?>
        </td>
        <td><?php echo $form->labelEx($model,"camion_id",array('style'=>'width:80px;'));?></td>
        <td><?php echo $form->dropDownList($model,'camion_id',CHtml::listData(CamionArrendado::model()->listar(), 'id', 'nombre'));?></td>
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
				        'style'=>'width:90px;',
						'readonly'=>'readonly',
				    ),
				)
			);
		?>
        </td>		
    <td>
        <?php echo $form->labelEx($model,"reporte",array('style'=>'width:80px;'));?>
    </td>
    <td>
        <?php echo $form->textField($model,'reporte');?>
    </td>
 </tr>	
 <tr>
    <td><?php echo CHtml::submitButton('Filtrar',['class'=>'btn btn-primary']); ?></div>
  </td>
 </tr>
</table>
	

	
<?php $this->endWidget(); ?>

</div><!-- search-form -->