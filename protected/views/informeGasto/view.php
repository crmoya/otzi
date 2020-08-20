<?php
/* @var $this InformeGastoController */
/* @var $model InformeGasto */

$this->breadcrumbs=array(
	'Volver a Gastos'=>array('//gastoCompleta/admin?policy='.$model->politica_id),
	$model->id,
);


?>
<?php if($model->estado == 1):
	$datetime1 = date_create($model->fecha_cierre);
    $datetime2 = date_create($model->fecha_envio);
    $interval = date_diff($datetime1, $datetime2);
    $dias = $interval->format("%d");
?>
	<h4 class="exito">
	<b>Informe cerrado.</b> Este informe de gastos fue cerrado el <b><?=Tools::backFecha($model->fecha_cierre)?></b>. El proceso de revisión tardó <b><?=$dias?></b> día(s) desde la fecha de envío.
	</h4>
<?php else:?>
	<h4 class="alerta">
	<?=$model->aprobado_por;?> está a cargo de continuar con el proceso de revisión de este informe de gastos.
	</h4>
<?php endif;?>

<div class="bloque container">
	<h2><?=$model->titulo?></h2>
	<table>
		<tr>
			<td>Folio:</td>
			<td><?=$model->numero?></td>
			<td>Gastos:</td>
			<td><?=$model->nro_gastos?></td>
		</tr>
		<tr>
			<td>Rendidor:</td>
			<td><?=$model->nombre_empleado. " (".$model->rut_empleado.")"?></td>
			<td>Aprobado:</td>
			<td><?="$ ".number_format($model->total_aprobado,0,",",".")?></td>
		</tr>
		<tr>
			<td>Política:</td>
			<td><?=$model->politica?></td>
			<td>Total:</td>
			<td><?="$ ".number_format($model->total,0,",",".")?></td>
		</tr>
		<tr>
			<td>Envío:</td>
			<td><?=Tools::backFecha($model->fecha_envio)?></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<div class="row">
		<table class="table">
			<thead>
				<tr>
					<th>Estado</th>
					<th>Gasto</th>
					<th>Fecha</th>
					<th>Categoría</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$gastos = $model->gastos; 
				foreach($gastos as $gasto):
				?>
				<tr>
					<td><div class="estado"></div></td>
					<td>Gasto</td>
					<td>Fecha</td>
					<td>Categoría</td>
					<td>Total</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>

<style>
.table{
	padding-top: 20px;
}
.exito{
	background:#dff0d8;
	color: green;
	padding: 5px;
	width: 100%;
	border-radius: 5px;
	font-size: 11pt;
}
.alerta{
	background: #fcf8e3;
	color: #8a6d3b;
	padding: 5px;
	width: 100%;
	border-radius: 5px;
	font-size: 11pt;
}
.span-19{
	width: 100% !important;
}
.bloque{
	border: 1px solid gray;
	border-radius: 5px;
	padding: 10px;
}
</style>