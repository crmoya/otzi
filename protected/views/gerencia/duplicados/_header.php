

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/redmond/jquery-ui.css"/>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>


<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route,['policy'=>$model->policy,'remuneraciones'=>$model->es_remuneraciones]),
	'method'=>'get',
)); 

?>

<h3><?=Yii::app()->getController()->pageTitle?></h3>

<table>
 <tr>
<?php if($model->es_remuneraciones == 0):?>
  <td>
  	<?php echo $form->label($model,'igual'); ?>
  </td>
  <td>
  	<?php echo $form->dropDownList($model,'igual', CHtml::listData(array(array('id'=>'TODOS','nombre'=>'TODOS'),array('id'=>'SIN ERRORES','nombre'=>'SIN ERRORES'),array('id'=>'CON ERRORES','nombre'=>'CON ERRORES')), 'id', 'nombre')); ?>
  </td>
<?php endif;?>
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
					'dateFormat'=>'yy-mm-dd',
					'changeYear'=>true,
					'changeMonth'=>true,
				),
				'htmlOptions'=>array(
					'style'=>'width:90px;',
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
					'dateFormat'=>'yy-mm-dd',
					'changeYear'=>true,
					'changeMonth'=>true,
				),
				'htmlOptions'=>array(
					'style'=>'width:90px;',
				),
			)
		);
	?>
	</td>
	<td>
		<?php echo CHtml::submitButton('Filtrar',['class'=>'btn btn-primary']); ?>
	</td>
 </tr>
</table>
<?php $this->endWidget(); ?>