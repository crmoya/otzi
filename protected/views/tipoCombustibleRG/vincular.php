<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

?>

<h3>Tipos de combustible actualmente vinculados</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tipocombustible-grid',
	'dataProvider'=>$model->search(0),
	'filter'=>$model,
	'columns'=>array(
		'tipocombustible',
		['name'=>"tipoCombustible_id",'value'=>'isset($data->tc)?$data->tc->nombre:""'],
		array(
			'class'=>'CButtonColumn',
			'template' => '{delete}',
		),
	),
)); ?>

<h3>Tipos de combustible no vinculadas</h3>
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
				<?php echo $form->labelEx($modelForm,'tipocombustible'); ?>
				<?php echo $form->dropDownList($modelForm,'tipocombustible', CHtml::listData(TipoCombustibleRG::model()->listar(), 'tipocombustible', 'tipocombustible'),['style'=>'width:400px;']); ?>
				<?php echo $form->error($modelForm,'tipocombustible'); ?>
			</td>
			<td>
				<?php echo $form->labelEx($modelForm,'tipocombustiblesam'); ?>
				<?php echo $form->dropDownList($modelForm,'tipocombustiblesam', CHtml::listData(TipoCombustible::model()->findAll(['order'=>'nombre']), 'id', 'nombre'),['style'=>'width:400px;']); ?>
				<?php echo $form->error($modelForm,'tipocombustiblesam'); ?>
			</td>
		</tr>	
	</table>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Vincular',['class'=>'btn btn-primary form-control']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


