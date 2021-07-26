<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

?>

<h3>Faenas actualmente vinculadas</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'faena-grid',
	'dataProvider'=>$model->search(0),
	'filter'=>$model,
	'columns'=>array(
		'faena',
		['name'=>"faena_id",'value'=>'isset($data->cg)?$data->cg->nombre:""'],
		array(
			'class'=>'CButtonColumn',
			'template' => '{delete}',
		),
	),
)); ?>

<h3>Faenas no vinculadas</h3>
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
				<?php echo $form->labelEx($modelForm,'faena'); ?>
				<?php echo $form->dropDownList($modelForm,'faena', CHtml::listData(FaenaRindegasto::model()->listarNoVinculados(), 'faena', 'faena'),['style'=>'width:400px;']); ?>
				<?php echo $form->error($modelForm,'faena'); ?>
			</td>
			<td>
				<?php echo $form->labelEx($modelForm,'faenasam'); ?>
				<?php echo $form->dropDownList($modelForm,'faenasam', CHtml::listData(Faena::model()->findAll(['order'=>'nombre']), 'id', 'nombre'),['style'=>'width:400px;']); ?>
				<?php echo $form->error($modelForm,'faenasam'); ?>
			</td>
		</tr>	
	</table>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Vincular',['class'=>'btn btn-primary form-control']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


