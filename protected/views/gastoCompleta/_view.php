<?php
/* @var $this GastoCompletaController */
/* @var $data GastoCompleta */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('retenido')); ?>:</b>
	<?php echo CHtml::encode($data->retenido); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cantidad')); ?>:</b>
	<?php echo CHtml::encode($data->cantidad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('centro_costo_faena')); ?>:</b>
	<?php echo CHtml::encode($data->centro_costo_faena); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departamento')); ?>:</b>
	<?php echo CHtml::encode($data->departamento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('faena')); ?>:</b>
	<?php echo CHtml::encode($data->faena); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('impuesto_especifico')); ?>:</b>
	<?php echo CHtml::encode($data->impuesto_especifico); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('iva')); ?>:</b>
	<?php echo CHtml::encode($data->iva); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('km_carguio')); ?>:</b>
	<?php echo CHtml::encode($data->km_carguio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('litros_combustible')); ?>:</b>
	<?php echo CHtml::encode($data->litros_combustible); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('monto_neto')); ?>:</b>
	<?php echo CHtml::encode($data->monto_neto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre_quien_rinde')); ?>:</b>
	<?php echo CHtml::encode($data->nombre_quien_rinde); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nro_documento')); ?>:</b>
	<?php echo CHtml::encode($data->nro_documento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('periodo_planilla')); ?>:</b>
	<?php echo CHtml::encode($data->periodo_planilla); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rut_proveedor')); ?>:</b>
	<?php echo CHtml::encode($data->rut_proveedor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supervisor_combustible')); ?>:</b>
	<?php echo CHtml::encode($data->supervisor_combustible); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipo_documento')); ?>:</b>
	<?php echo CHtml::encode($data->tipo_documento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unidad')); ?>:</b>
	<?php echo CHtml::encode($data->unidad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehiculo_equipo')); ?>:</b>
	<?php echo CHtml::encode($data->vehiculo_equipo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehiculo_oficina_central')); ?>:</b>
	<?php echo CHtml::encode($data->vehiculo_oficina_central); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gasto_id')); ?>:</b>
	<?php echo CHtml::encode($data->gasto_id); ?>
	<br />

	*/ ?>

</div>