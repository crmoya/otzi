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
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('horasMin')); ?>:</b>
	<?php echo CHtml::encode($data->horasMin); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('precioUnitario')); ?>:</b>
	<?php echo CHtml::encode($data->precioUnitario); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('consumoEsperado')); ?>:</b>
	<?php echo CHtml::encode($data->consumoEsperado); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('valorHora')); ?>:</b>
	<?php echo CHtml::encode($data->valorHora); ?>
	<br />


</div>