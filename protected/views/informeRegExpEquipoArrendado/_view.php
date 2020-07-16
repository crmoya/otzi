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

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipo')); ?>:</b>
	<?php echo CHtml::encode($data->equipo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('horasReales')); ?>:</b>
	<?php echo CHtml::encode($data->horasReales); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('combustible')); ?>:</b>
	<?php echo CHtml::encode($data->combustible); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('repuesto')); ?>:</b>
	<?php echo CHtml::encode($data->repuesto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('horasPanne')); ?>:</b>
	<?php echo CHtml::encode($data->horasPanne); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_reg')); ?>:</b>
	<?php echo CHtml::encode($data->id_reg); ?>
	<br />

	*/ ?>

</div>