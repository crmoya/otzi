<?php
/* @var $this ExpedicionesController */
/* @var $data Expediciones */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nVueltas')); ?>:</b>
	<?php echo CHtml::encode($data->nVueltas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('totalTransportado')); ?>:</b>
	<?php echo CHtml::encode($data->totalTransportado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total')); ?>:</b>
	<?php echo CHtml::encode($data->total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kmRecorridos')); ?>:</b>
	<?php echo CHtml::encode($data->kmRecorridos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->vehiculo); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('chofer')); ?>:</b>
	<?php echo CHtml::encode($data->chofer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('propio_arrendado')); ?>:</b>
	<?php echo CHtml::encode($data->propio_arrendado); ?>
	<br />

	*/ ?>

</div>