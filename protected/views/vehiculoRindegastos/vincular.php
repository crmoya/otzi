<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

?>

<h2>Asociar registros no vinculados de RindeGastos</h2>


<h4>Vehículos actualmente vinculados</h4>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'faena-grid',
	'dataProvider'=>$model->search(0),
	'filter'=>$model,
	'columns'=>array(
		'vehiculo',
		array(
			'class'=>'CButtonColumn',
			'template' => '{delete}',
		),
	),
)); ?>

<h4>Vehículos no vinculados</h4>
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
		<?php echo CHtml::submitButton('Vincular'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


