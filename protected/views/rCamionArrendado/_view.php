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

	<b><?php echo CHtml::encode($data->getAttributeLabel('ordenCompra')); ?>:</b>
	<?php echo CHtml::encode($data->ordenCompra); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('camionArrendado_id')); ?>:</b>
	<?php echo CHtml::encode($data->camionArrendado_id); ?>
	<br />


</div>