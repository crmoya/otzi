<?php
/* @var $this InformeGastoController */
/* @var $data InformeGasto */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('titulo')); ?>:</b>
	<?php echo CHtml::encode($data->titulo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numero')); ?>:</b>
	<?php echo CHtml::encode($data->numero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_envio')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_envio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_cierre')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_cierre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre_empleado')); ?>:</b>
	<?php echo CHtml::encode($data->nombre_empleado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rut_empleado')); ?>:</b>
	<?php echo CHtml::encode($data->rut_empleado); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('aprobado_por')); ?>:</b>
	<?php echo CHtml::encode($data->aprobado_por); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('politica_id')); ?>:</b>
	<?php echo CHtml::encode($data->politica_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('politica')); ?>:</b>
	<?php echo CHtml::encode($data->politica); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total')); ?>:</b>
	<?php echo CHtml::encode($data->total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_aprobado')); ?>:</b>
	<?php echo CHtml::encode($data->total_aprobado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nro_gastos')); ?>:</b>
	<?php echo CHtml::encode($data->nro_gastos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nro_gastos_aprobados')); ?>:</b>
	<?php echo CHtml::encode($data->nro_gastos_aprobados); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nro_gastos_rechazados')); ?>:</b>
	<?php echo CHtml::encode($data->nro_gastos_rechazados); ?>
	<br />

	*/ ?>

</div>