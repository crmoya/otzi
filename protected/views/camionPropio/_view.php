<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('codigo')); ?>:</b>
	<?php echo CHtml::encode($data->codigo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('capacidad')); ?>:</b>
	<?php echo CHtml::encode($data->capacidad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pesoOVolumen')); ?>:</b>
	<?php echo CHtml::encode($data->pesoOVolumen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('consumoPromedio')); ?>:</b>
	<?php echo CHtml::encode($data->consumoPromedio); ?>
	<br />

</div>