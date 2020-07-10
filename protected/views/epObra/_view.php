<?php
/* @var $this EpObraController */
/* @var $data EpObra */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('produccion')); ?>:</b>
	<?php echo CHtml::encode($data->produccion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('costo')); ?>:</b>
	<?php echo CHtml::encode($data->costo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reajuste')); ?>:</b>
	<?php echo CHtml::encode($data->reajuste); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('retencion')); ?>:</b>
	<?php echo CHtml::encode($data->retencion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descuento')); ?>:</b>
	<?php echo CHtml::encode($data->descuento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mes')); ?>:</b>
	<?php echo CHtml::encode($data->mes); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('agno')); ?>:</b>
	<?php echo CHtml::encode($data->agno); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comentarios')); ?>:</b>
	<?php echo CHtml::encode($data->comentarios); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resoluciones_id')); ?>:</b>
	<?php echo CHtml::encode($data->resoluciones_id); ?>
	<br />

	*/ ?>

</div>