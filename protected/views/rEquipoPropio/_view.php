<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reporte')); ?>:</b>
	<?php echo CHtml::encode($data->reporte); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->observaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipoPropio_id')); ?>:</b>
	<?php echo CHtml::encode($data->equipoPropio_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hInicial')); ?>:</b>
	<?php echo CHtml::encode($data->hInicial); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hFinal')); ?>:</b>
	<?php echo CHtml::encode($data->hFinal); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('horas')); ?>:</b>
	<?php echo CHtml::encode($data->horas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('faena_id')); ?>:</b>
	<?php echo CHtml::encode($data->faena_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operador_id')); ?>:</b>
	<?php echo CHtml::encode($data->operador_id); ?>
	<br />

	*/ ?>

</div>