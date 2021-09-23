<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

?>

<h3>Vehículos actualmente vinculados</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'faena-grid',
	'dataProvider'=>$model->search(0),
	'filter'=>$model,
	'columns'=>array(
		'vehiculo',
		[
			'name'=>"camionpropio_id",'value'=>'isset($data->camionPropio)?$data->camionPropio->nombre." (".$data->camionPropio->codigo.")":""',
			'filter'=>CHtml::listData(CamionPropio::model()->listarEnRG(), 'id', 'nombre'),
		],
		[
			'name'=>"camionarrendado_id",'value'=>'isset($data->camionArrendado)?$data->camionArrendado->nombre:""',
			'filter'=>CHtml::listData(CamionArrendado::model()->listarEnRG(), 'id', 'nombre'),
		],
		[
			'name'=>"equipopropio_id",'value'=>'isset($data->equipoPropio)?$data->equipoPropio->nombre." (".$data->equipoPropio->codigo.")":""',
			'filter'=>CHtml::listData(EquipoPropio::model()->listarEnRG(), 'id', 'nombre'),
		],
		[
			'name'=>"equipoarrendado_id",'value'=>'isset($data->equipoArrendado)?$data->equipoArrendado->nombre:""',
			'filter'=>CHtml::listData(EquipoArrendado::model()->listarEnRG(), 'id', 'nombre'),
		],
		array(
			'class'=>'CButtonColumn',
			'template' => '{delete}',
		),
	),
)); ?>

<h3>Vehículos no vinculados</h3>
<div class="form" style="width:1000px;">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rindegastos-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($modelForm); ?>
	
	<table style="width:900px;">
		<tr>
			<th>Registro en Rindegastos</th>
			<th>Registro en SAM</th>
		</tr>
		<tr>
			<td>
				<?php echo $form->labelEx($modelForm,'vehiculo'); ?>
				<?php echo $form->dropDownList($modelForm,'vehiculo', CHtml::listData(VehiculoRindegastos::model()->listarNoVinculados(), 'vehiculo', 'vehiculo'),['style'=>'width:400px;']); ?>
				<?php echo $form->error($modelForm,'vehiculo'); ?>
			</td>
			<td>
				<?php echo $form->labelEx($modelForm,'vehiculosam'); ?>
				<?php echo $form->dropDownList($modelForm,'vehiculosam', CHtml::listData(VehiculoRindegastos::model()->listarSam(), 'id', 'nombre'),['style'=>'width:400px;']); ?>
				<?php echo $form->error($modelForm,'vehiculosam'); ?>
			</td>
		</tr>	
	</table>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Vincular',['class'=>'btn btn-primary form-control']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


