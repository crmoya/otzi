<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Registro de expedición de Equipo Arrendado #<?php echo $model->id;?>:</h3>
<div class="form" style="width:900px;">
	<fieldset>
		<legend>Reporte de Equipo Arrendado</legend>
		<table>
			<tr>
				<td style='font-size:0.9em;'><b>Fecha:</b></td>
				<td><?php echo CHtml::encode($model->fecha);?></td>
				<td style='font-size:0.9em;'><b>Reporte:</b></td>
				<td><?php echo CHtml::encode($model->reporte);?></td>
			</tr>
			<tr>
				<td style='font-size:0.9em;'><b>Equipo:</b></td>
				<td><?php echo CHtml::encode($equipo->nombre);?></td>
				<td style='font-size:0.9em;'><b>Operador:</b></td>
				<td ><?php echo CHtml::encode($operador->nombre);?></td>
			</tr>
			<tr>
			 	<td style='font-size:0.9em;'><b>Orden de Compra o Contrato N°:</b></td>
				<td><?php echo CHtml::encode($model->ordenCompra);?></td>
				<td style='font-size:0.9em;'><b>Propietario:</b></td>
				<td><?php echo CHtml::encode(Propietario::model()->getNombre($equipo->propietario_id));?></td>
			</tr>
			<tr>
				<td style='font-size:0.9em;'><b>Horómetro Inicial:</b></td>
				<td><?php echo CHtml::encode(number_format($model->hInicial,2,',','.'));?></td>
				<td style='font-size:0.9em;'><b>Horómetro Final:</b></td>
				<td ><?php echo CHtml::encode(number_format($model->hFinal,2,',','.'));?></td>
			</tr>
			<tr>
				<td style='font-size:0.9em;'><b>Horas:</b></td>
				<td><?php echo CHtml::encode(number_format($model->horas,2,',','.'));?></td>
			
				<td style='font-size:0.9em;'><b>Horómetro GPS:</b></td>
				<td ><?php echo CHtml::encode(number_format($model->horasGps,2,',','.'));?></td>
				<td style='font-size:0.9em;'></td>
				<td></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset id="cargaComb">
		<legend>Datos de carga de combustible</legend>
		<table cellspacing="0">
			<tbody>
			<?php foreach($cargas as $combustible):?>
				<tr>
					<td width="100px">
						<table style="border:solid 1px silver;padding:10px;">
							<tr>
							  <td style='font-size:0.9em;'><b>Faena:</b></td>
							  <td><?php echo Faena::model()->getNombre(CHtml::encode($combustible['faena_id']));?></td>
							  <td style='font-size:0.9em;'><b>Combustible Lts:</b></td>
							  <td><?php echo CHtml::encode(number_format($combustible['petroleoLts'],2,',','.'));?></td>
							  <td style='font-size:0.9em;'><b>Hr. Carguío:</b></td>
							  <td><?php echo CHtml::encode(number_format($combustible['hCarguio'],2,',','.'));?></td>															  
							</tr>
							<tr>
							  <td style='font-size:0.9em;'><b>Precio Unitario:</b></td>
							  <td><?php echo CHtml::encode("$".number_format($combustible['precioUnitario'],0,',','.'));?></td>
							  <td style='font-size:0.9em;'><b>Valor Total:</b></td>
							  <td><?php echo CHtml::encode("$".number_format($combustible['valorTotal'],0,',','.'));?></td>
							  <td style='font-size:0.9em;'><b>Tipo Combustible:</b></td>
							  <td><?php echo TipoCombustible::model()->getNombre(CHtml::encode($combustible['tipoCombustible_id']));?></td>														  
							</tr>
							<tr>
							  <td style='font-size:0.9em;'><b>Supervisor Combustible:</b></td>
							  <td><?php echo SupervisorCombustible::model()->getNombre(CHtml::encode($combustible['supervisorCombustible_id']));?></td>
							  <td style='font-size:0.9em;'><b>Guía:</b></td>
							  <td><?php echo CHtml::encode($combustible['guia']);?></td>
							  <td style='font-size:0.9em;'><b>Factura:</b></td>
							  <td><?php echo CHtml::encode($combustible['factura']);?></td>
							</tr>
							<tr>
							  <td style='font-size:0.9em;'><b>N°Rendición:</b></td>
							  <td><?php echo CHtml::encode($combustible['numero']);?></td>
							  <td style='font-size:0.9em;'><b>Nombre quien rinde:</b></td>
							  <td><?php echo CHtml::encode($combustible['nombre']);?></td>
							  <td style='font-size:0.9em;'><b>Fecha de Documento:</b></td>
							  <td><?php echo CHtml::encode($combustible['fechaRendicion']);?></td>
							</tr>
						</table>	
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</fieldset>
	<fieldset id="cargaComb">
		<legend>Datos de compra de Repuestos</legend>
		<table cellspacing="0">
			<tbody>
			<?php foreach($compras as $compra):?>
				<tr>
					<td width="100px">
						<table style="border:solid 1px silver;padding:10px;">
							<tr>
							  <td style='font-size:0.9em;'><b>Repuesto:</b></td>
							  <td><?php echo CHtml::encode($compra['repuesto']);?></td>
							  <td style='font-size:0.9em;'><b>Monto Neto:</b></td>
							  <td><?php echo CHtml::encode("$".number_format($compra['montoNeto'],0,',','.'));?></td>
							  <td style='font-size:0.9em;'><b>Cantidad:</b></td>
							  <td><?php echo CHtml::encode($compra['cantidad']." ".Tools::getNombreUnidad($compra['unidad']));?></td>															  
							</tr>
							<tr>
							  <td style='font-size:0.9em;'><b>Guía:</b></td>
							  <td><?php echo CHtml::encode($compra['guia']);?></td>
							  <td style='font-size:0.9em;'><b>Factura:</b></td>
							  <td><?php echo CHtml::encode($compra['factura']);?></td>
							  <td style='font-size:0.9em;'><b>Faena:</b></td>
							  <td><?php echo CHtml::encode(Faena::model()->getNombre(CHtml::encode($compra['faena_id'])));?></td>														  
							</tr>
							<tr>
							  <td style='font-size:0.9em;'><b>N°Rendición:</b></td>
							  <td><?php echo CHtml::encode($compra['numero']);?></td>
							  <td style='font-size:0.9em;'><b>Nombre quien rinde:</b></td>
							  <td><?php echo CHtml::encode($compra['nombre']);?></td>
							  <td style='font-size:0.9em;'><b>Fecha de Documento:</b></td>
							  <td><?php echo CHtml::encode($compra['fechaRendicion']);?></td>
							</tr>
						</table>	
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</fieldset>
	
	<?php if($model->panne == 1):?>
	<fieldset>
		<legend>Datos de Panne</legend>
		<table>
			<tr>
				<td>Hora Inicio: <?php echo CHtml::encode($model->iniPanne);?></td>
				<td>Hora Fin: <?php echo CHtml::encode($model->finPanne);?></td>
			</tr>
		</table>
	</fieldset>
	<?php endif;?>
	
	
	<fieldset>
		<legend>Observaciones Maquinaria</legend>
		<div style='width:850px;overflow:auto;'>
			<?php echo CHtml::encode($model->observaciones);?>
		</div>
	</fieldset>
    
        <fieldset>
		<legend>Observaciones Obra</legend>
		<div style='width:850px;overflow:auto;'>
			<?php echo CHtml::encode($model->observaciones_obra);?>
		</div>
	</fieldset>
</div>

