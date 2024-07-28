<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/redmond/jquery-ui.css" />
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>

<?php $form = $this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'get',
)); ?>

<h3><?= Yii::app()->getController()->pageTitle ?></h3>

<div class="row mt-4">
	<div class="col-md-2">
		<?php echo $form->label($model, 'fecha_inicio'); ?><br />
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $model,
			'language' => 'es',
			'attribute' => 'fecha_inicio',
			'options' => array(
				'showAnim' => 'fold',
				'dateFormat' => 'yy-mm-dd',
				'changeYear' => true,
				'changeMonth' => true,
			),
			'htmlOptions' => array(
				'style' => 'width:90px;',
			),
		));
		?>
	</div>
	<div class="col-md-2">
		<?php echo $form->label($model, 'fecha_fin'); ?><br />
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $model,
			'language' => 'es',
			'attribute' => 'fecha_fin',
			'options' => array(
				'showAnim' => 'fold',
				'dateFormat' => 'yy-mm-dd',
				'changeYear' => true,
				'changeMonth' => true,
			),
			'htmlOptions' => array(
				'style' => 'width:90px;',
			),
		));
		?>
	</div>
	<div class="col-md-2 offset-md-1">
		<?php echo $form->labelEx($model, 'chkKms'); ?>
		<?php echo $form->checkBox($model, 'chkKms', array('checked' => $model->chkKms == 1 ? "checked" : "")); ?><br />

		<?php echo $form->labelEx($model, 'chkKmsGPS'); ?>
		<?php echo $form->checkBox($model, 'chkKmsGPS', array('checked' => $model->chkKmsGPS == 1 ? "checked" : "")); ?><br />

		<?php echo $form->labelEx($model, 'chkHrs'); ?>
		<?php echo $form->checkBox($model, 'chkHrs', array('checked' => $model->chkHrs == 1 ? "checked" : "")); ?><br />
	</div>
	<div class="col-md-2">
		<?php echo $form->labelEx($model, 'chkProduccion'); ?>
		<?php echo $form->checkBox($model, 'chkProduccion', array('checked' => $model->chkProduccion == 1 ? "checked" : "")); ?><br />

		<?php echo $form->labelEx($model, 'chkCombLts'); ?>
		<?php echo $form->checkBox($model, 'chkCombLts', array('checked' => $model->chkCombLts == 1 ? "checked" : "")); ?><br />

		<?php echo $form->labelEx($model, 'chkRepuestos'); ?>
		<?php echo $form->checkBox($model, 'chkRepuestos', array('checked' => $model->chkRepuestos == 1 ? "checked" : "")); ?><br />
	</div>
	<div class="col-md-2">
		<?php echo $form->labelEx($model, 'chkRemuneraciones'); ?>
		<?php echo $form->checkBox($model, 'chkRemuneraciones', array('checked' => $model->chkRemuneraciones == 1 ? "checked" : "")); ?><br />

		<?php echo $form->labelEx($model, 'chkHrsPanne'); ?>
		<?php echo $form->checkBox($model, 'chkHrsPanne', array('checked' => $model->chkHrsPanne == 1 ? "checked" : "")); ?><br />

		<?php echo $form->labelEx($model, 'chkPanne'); ?>
		<?php echo $form->checkBox($model, 'chkPanne', array('checked' => $model->chkPanne == 1 ? "checked" : "")); ?><br />
	</div>

	<div class="row mt-2 mb-4">
		<div class="col-md-2">
			<?php echo $form->labelEx($model, 'reporte'); ?>
			<?php echo $form->textField($model, 'reporte', array('size' => 10)); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model, 'camion_id'); ?><br />
			<?php echo $form->dropDownList($model, 'camion_id', CHtml::listData(CamionPropio::model()->listar(), 'id', 'nombre')); ?>
			<?php echo $form->hiddenField($model, 'faena_id'); ?>
		</div>
		<div class="col-md-4 offset-md-1">
			<div class="btn btn-info exportar-adjuntos">Exportar adjuntos</div>
			<?php echo CHtml::submitButton('Filtrar', ['class' => 'btn btn-primary']); ?>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>