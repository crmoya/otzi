<?php $this->pageTitle = Yii::app()->name; ?>
<h3>Registro de expedición de camión, camioneta, auto Propio #<?php echo $model->id; ?>:</h3>
<div class="form" style="width:900px;">
	<fieldset>
		<legend>Reporte de camión, camioneta, auto Propio</legend>
		<table>
			<tr>
				<td style='font-size:0.9em;'><b>Fecha:</b></td>
				<td><?php echo CHtml::encode($model->fecha); ?></td>
				<td style='font-size:0.9em;'><b>camión, camioneta, auto:</b></td>
				<td><?php echo CHtml::encode($camion->nombre); ?></td>
			</tr>
			<tr>
				<td style='font-size:0.9em;'><b>Chofer:</b></td>
				<td><?php echo CHtml::encode($chofer->nombre); ?></td>
				<td style='font-size:0.9em;'><b>Reporte:</b></td>
				<td><?php echo CHtml::encode($model->reporte); ?></td>

			</tr>
			<tr>
				<td style='font-size:0.9em;'><b>Código camión, camioneta, auto:</b></td>
				<td><?php echo CHtml::encode($camion->codigo); ?></td>
				<td style='font-size:0.9em;'><b>Capacidad:</b></td>
				<td><?php echo CHtml::encode(number_format($camion->capacidad, 2, ',', '.')); ?></td>
			</tr>
			<tr>
				<td style='font-size:0.9em;'><b>Km Inicial:</b></td>
				<td><?php echo CHtml::encode(number_format($model->kmInicial, 2, ',', '.')); ?></td>
				<td style='font-size:0.9em;'><b>Km Final:</b></td>
				<td><?php echo CHtml::encode(number_format($model->kmFinal, 2, ',', '.')); ?></td>
			</tr>
			<tr>
				<td style='font-size:0.9em;'><b>Km GPS:</b></td>
				<td><?php echo CHtml::encode(number_format($model->kmGps, 2, ',', '.')); ?></td>
				<td style='font-size:0.9em;'><b>Km Recorridos:</b></td>
				<td><?php echo CHtml::encode(number_format($recorridos, 2, ',', '.')); ?></td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>Expediciones con PU por volumen</legend>
		<table cellspacing="0">
			<tbody>
				<?php foreach ($viajes as $expedicion) : ?>
					<tr>
						<td width="100px">
							<table style="border:solid 1px silver;padding:10px;">
								<tr>
									<td style='font-size:0.9em;'><b>Faena:</b></td>
									<td><?php echo CHtml::encode(Faena::model()->getNombre($expedicion['faena_id'])); ?></td>
									<td style='font-size:0.9em;'><b>Origen / Destino:</b></td>
									<td><?php echo CHtml::encode(OrigendestinoFaena::model()->getNombre($expedicion['origendestino_faena_id'])); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>N°Vueltas:</b></td>
									<td><?php echo CHtml::encode(number_format($expedicion['nVueltas'], 0, ',', '.')); ?></td>
									<td style='font-size:0.9em;'><b>Km Recorridos:</b></td>
									<td><?php echo CHtml::encode(number_format($expedicion['kmRecorridos'], 2, ',', '.')); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Coeficiente Carga Parcial %:</b></td>
									<td><?php echo CHtml::encode($expedicion['coeficiente'] . "%"); ?></td>
									<td style='font-size:0.9em;'><b>PU:</b></td>
									<td><?php echo CHtml::encode($expedicion['pu']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Total Transportado:</b></td>
									<td><?php echo CHtml::encode(number_format($expedicion['totalTransportado'], 2, ',', '.')); ?></td>
									<td style='font-size:0.9em;'><b>Total:</b></td>
									<td><?php echo CHtml::encode("$" . number_format($expedicion['total'], 0, ',', '.')); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>

	<fieldset>
		<legend>Expediciones con PU por tiempo</legend>
		<table cellspacing="0">
			<tbody>
				<?php foreach ($viajesT as $viajeT) : ?>
					<tr>
						<td width="100px">
							<table style="border:solid 1px silver;padding:10px;">
								<tr>
									<td style='font-size:0.9em;'><b>Faena:</b></td>
									<td><?php echo CHtml::encode(Faena::model()->getNombre($viajeT['faena_id'])); ?></td>
									<td style='font-size:0.9em;'><b>Unidad:</b></td>
									<td colspan="3"><?php echo CHtml::encode($viajeT->unidadfaena->getUnidad($viajeT->unidadfaena->unidad) . ($viajeT->unidadfaena->observaciones!=""?" (" . $viajeT->unidadfaena->observaciones . ")":"")); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Cantidad:</b></td>
									<td><?php echo CHtml::encode($viajeT['cantidad']); ?></td>
									<td style='font-size:0.9em;'><b>PU:</b></td>
									<td><?php echo CHtml::encode($viajeT->unidadfaena->pu); ?></td>
									<td style='font-size:0.9em;'><b>Total:</b></td>
									<td><?php echo CHtml::encode($viajeT['total']); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>

	<fieldset id="cargaComb">
		<legend>Datos de carga de combustible</legend>
		<table cellspacing="0">
			<tbody>
				<?php foreach ($cargas as $combustible) : ?>
					<tr>
						<td width="100px">
							<table style="border:solid 1px silver;padding:10px;">
								<tr>
									<td style='font-size:0.9em;'><b>Faena:</b></td>
									<td><?php echo Faena::model()->getNombre(CHtml::encode($combustible['faena_id'])); ?></td>
									<td style='font-size:0.9em;'><b>Tipo documento:</b></td>
									<td><?php echo CHtml::encode(Tools::getTipoDocumento($combustible['tipo_documento'])); ?></td>
									<td style='font-size:0.9em;'><b>Nº documento:</b></td>
									<td><?php echo CHtml::encode($combustible['factura']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Rut proveedor:</b></td>
									<td><?php echo CHtml::encode($combustible['rut_proveedor']); ?></td>
									<td style='font-size:0.9em;'><b>Nombre proveedor:</b></td>
									<td><?php echo CHtml::encode($combustible['nombre_proveedor']); ?></td>
									<td style='font-size:0.9em;'><b>Combustible Lts:</b></td>
									<td><?php echo CHtml::encode(number_format($combustible['petroleoLts'], 2, ',', '.')); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Km Carguío:</b></td>
									<td><?php echo CHtml::encode(number_format($combustible['kmCarguio'], 2, ',', '.')); ?></td>
									<td style='font-size:0.9em;'><b>Precio Unitario:</b></td>
									<td><?php echo CHtml::encode("$" . number_format($combustible['precioUnitario'], 0, ',', '.')); ?></td>
									<td style='font-size:0.9em;'><b>Valor Total:</b></td>
									<td><?php echo CHtml::encode("$" . number_format($combustible['valorTotal'], 0, ',', '.')); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Tipo Combustible:</b></td>
									<td><?php echo TipoCombustible::model()->getNombre(CHtml::encode($combustible['tipoCombustible_id'])); ?></td>
									<td style='font-size:0.9em;'><b>Supervisor Combustible:</b></td>
									<td><?php echo SupervisorCombustible::model()->getNombre(CHtml::encode($combustible['supervisorCombustible_id'])); ?></td>
									<td style='font-size:0.9em;'><b>N°Rendición:</b></td>
									<td><?php echo CHtml::encode($combustible['numero']); ?></td>
									
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Rut quien rinde:</b></td>
									<td><?php echo CHtml::encode($combustible['rut_rinde']); ?></td>
									<td style='font-size:0.9em;'><b>Fecha de Documento:</b></td>
									<td><?php echo CHtml::encode(Tools::backFecha($combustible['fechaRendicion'])); ?></td>
									<td style='font-size:0.9em;'><b>Nombre quien rinde:</b></td>
									<td><?php echo CHtml::encode($combustible['nombre']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Guía:</b></td>
									<td><?php echo CHtml::encode($combustible['guia']); ?></td>
									<td style='font-size:0.9em;'><b>Observaciones:</b></td>
									<td colspan="3"><?php echo CHtml::encode($combustible['observaciones']); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>
	<fieldset id="compraRepuesto">
		<legend>Datos de compra de Repuestos</legend>
		<table cellspacing="0">
			<tbody>
				<?php foreach ($compras as $compra) : ?>
					<tr>
						<td width="100px">
							<table style="border:solid 1px silver;padding:10px;">
								<tr>
									<td style='font-size:0.9em;'><b>Repuesto:</b></td>
									<td><?php echo CHtml::encode($compra['repuesto']); ?></td>
									<td style='font-size:0.9em;'><b>Monto Neto:</b></td>
									<td><?php echo CHtml::encode("$" . number_format($compra['montoNeto'], 0, ',', '.')); ?></td>
									<td style='font-size:0.9em;'><b>Tipo documento:</b></td>
									<td><?php echo CHtml::encode(Tools::getTipoDocumento($compra['tipo_documento'])); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Nº documento:</b></td>
									<td><?php echo CHtml::encode($compra['factura']); ?></td>
									<td style='font-size:0.9em;'><b>Rut proveedor:</b></td>
									<td><?php echo CHtml::encode($compra['rut_proveedor']); ?></td>
									<td style='font-size:0.9em;'><b>Nombre proveedor:</b></td>
									<td><?php echo CHtml::encode($compra['nombre_proveedor']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Cuenta:</b></td>
									<td><?php echo CHtml::encode($compra['cuenta']); ?></td>
									<td style='font-size:0.9em;'><b>Faena:</b></td>
									<td><?php echo CHtml::encode(Faena::model()->getNombre(CHtml::encode($compra['faena_id']))); ?></td>
									<td style='font-size:0.9em;'><b>Cantidad:</b></td>
									<td><?php echo CHtml::encode($compra['cantidad'] . " " . Tools::getNombreUnidad($compra['unidad'])); ?></td>
								</tr>
								<tr>
									
									<td style='font-size:0.9em;'><b>N°Rendición:</b></td>
									<td><?php echo CHtml::encode($compra['numero']); ?></td>
									<td style='font-size:0.9em;'><b>Rut quien rinde:</b></td>
									<td><?php echo CHtml::encode($compra['rut_rinde']); ?></td>
									<td style='font-size:0.9em;'><b>Nombre quien rinde:</b></td>
									<td><?php echo CHtml::encode($compra['nombre']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Fecha de Documento:</b></td>
									<td><?php echo CHtml::encode(Tools::backFecha($compra['fechaRendicion'])); ?></td>
									<td style='font-size:0.9em;'><b>Guía:</b></td>
									<td><?php echo CHtml::encode($compra['guia']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Observaciones:</b></td>
									<td colspan="3" ><?php echo CHtml::encode($compra['observaciones']); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>

	<fieldset id="remuneraciones">
		<legend>Datos de Remuneraciones</legend>
		<table cellspacing="0">
			<tbody>
				<?php foreach ($remuneraciones as $remuneracion) : ?>
					<tr>
						<td width="100px">
							<table style="border:solid 1px silver;padding:10px;">
								<tr>
									<td style='font-size:0.9em;'><b>Descripción:</b></td>
									<td><?php echo CHtml::encode($remuneracion['descripcion']); ?></td>
									<td style='font-size:0.9em;'><b>Monto Neto:</b></td>
									<td><?php echo CHtml::encode("$" . number_format($remuneracion['montoNeto'], 0, ',', '.')); ?></td>
									<td style='font-size:0.9em;'><b>Tipo documento:</b></td>
									<td><?php echo CHtml::encode(Tools::getTipoDocumento($remuneracion['tipo_documento'])); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Nº documento:</b></td>
									<td><?php echo CHtml::encode($remuneracion['documento']); ?></td>
									<td style='font-size:0.9em;'><b>Rut proveedor:</b></td>
									<td><?php echo CHtml::encode($remuneracion['rut_proveedor']); ?></td>
									<td style='font-size:0.9em;'><b>Nombre proveedor:</b></td>
									<td><?php echo CHtml::encode($remuneracion['nombre_proveedor']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Cuenta:</b></td>
									<td><?php echo CHtml::encode($remuneracion['cuenta']); ?></td>
									<td style='font-size:0.9em;'><b>Faena:</b></td>
									<td><?php echo CHtml::encode(Faena::model()->getNombre(CHtml::encode($remuneracion['faena_id']))); ?></td>
									<td style='font-size:0.9em;'><b>Cantidad:</b></td>
									<td><?php echo CHtml::encode($remuneracion['cantidad'] . " " . Tools::getNombreUnidad($remuneracion['unidad'])); ?></td>
								</tr>
								<tr>
									
									<td style='font-size:0.9em;'><b>N°Rendición:</b></td>
									<td><?php echo CHtml::encode($remuneracion['numero']); ?></td>
									<td style='font-size:0.9em;'><b>Rut quien rinde:</b></td>
									<td><?php echo CHtml::encode($remuneracion['rut_rinde']); ?></td>
									<td style='font-size:0.9em;'><b>Nombre quien rinde:</b></td>
									<td><?php echo CHtml::encode($remuneracion['nombre']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Fecha de Documento:</b></td>
									<td><?php echo CHtml::encode(Tools::backFecha($remuneracion['fechaRendicion'])); ?></td>
									<td style='font-size:0.9em;'><b>Guía:</b></td>
									<td><?php echo CHtml::encode($remuneracion['guia']); ?></td>
								</tr>
								<tr>
									<td style='font-size:0.9em;'><b>Observaciones:</b></td>
									<td colspan="3" ><?php echo CHtml::encode($remuneracion['observaciones']); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>

	<?php if ($model->panne == 1) : ?>
		<fieldset>
			<legend>Datos de Panne</legend>
			<table>
				<tr>
					<td>Hora Inicio: <?php echo CHtml::encode($model->iniPanne); ?></td>
					<td>Hora Fin: <?php echo CHtml::encode($model->finPanne); ?></td>
				</tr>
			</table>
		</fieldset>
	<?php endif; ?>


	<fieldset>
		<legend>Observaciones Camión</legend>
		<div style='width:850px;overflow:auto;'>
			<?php echo CHtml::encode($model->observaciones); ?>
		</div>
	</fieldset>

	<fieldset>
		<legend>Observaciones Obra</legend>
		<div style='width:850px;overflow:auto;'>
			<?php echo CHtml::encode($model->observaciones_obra); ?>
		</div>
	</fieldset>


</div>