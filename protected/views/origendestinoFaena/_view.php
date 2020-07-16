<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('origen_id')); ?>:</b>
	<?php echo CHtml::encode($data->origen_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('destino_id')); ?>:</b>
	<?php echo CHtml::encode($data->destino_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('faena_id')); ?>:</b>
	<?php echo CHtml::encode($data->faena_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pu')); ?>:</b>
	<?php echo CHtml::encode($data->pu); ?>
	<br />


</div>