<?php
/* @var $this GarantiasController */
/* @var $data Garantias */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numero')); ?>:</b>
	<?php echo CHtml::encode($data->numero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('monto')); ?>:</b>
	<?php echo CHtml::encode($data->monto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_vencimiento')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_vencimiento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('instituciones_id')); ?>:</b>
	<?php echo CHtml::encode($data->instituciones_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipos_garantias_id')); ?>:</b>
	<?php echo CHtml::encode($data->tipos_garantias_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contratos_id')); ?>:</b>
	<?php echo CHtml::encode($data->contratos_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('objetos_garantias_id')); ?>:</b>
	<?php echo CHtml::encode($data->objetos_garantias_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modificador_id')); ?>:</b>
	<?php echo CHtml::encode($data->modificador_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creador_id')); ?>:</b>
	<?php echo CHtml::encode($data->creador_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observacion')); ?>:</b>
	<?php echo CHtml::encode($data->observacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipo_monto')); ?>:</b>
	<?php echo CHtml::encode($data->tipo_monto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado_garantia')); ?>:</b>
	<?php echo CHtml::encode($data->estado_garantia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_devolucion')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_devolucion); ?>
	<br />

	*/ ?>

</div>