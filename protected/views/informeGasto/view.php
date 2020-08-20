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
				$total_aprobado = 0;
				$total = 0;
				$aprobados = 0;
				$rechazados = 0;
				foreach($gastos as $gasto):
					if($gasto->status==1){
						$total_aprobado += $gasto->total;
						$aprobados++;
					}
					else{
						$rechazados++;
					}
					$total += $gasto->total;
				?>
				<tr>
					<td width="80px"><div class="<?=$gasto->status==1?"verde":"rojo"?>"><?=$gasto->status==1?"aprobado":"rechazado"?></div></td>
					<td><a style="text-decoration:none;" href="<?=$gasto->imagen?>" target="_blank"><?=$gasto->supplier?></a></td>
					<td><?=Tools::backFecha($gasto->issue_date)?></td>
					<td><?=$gasto->category?></td>
					<td <?=$gasto->status!=1?'style="text-decoration: line-through;"':''?>><?="$ ".number_format($gasto->total,0,",",".")?></td>
				</tr>
				<?php endforeach;?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>Num. de Gastos</td>
					<td><?=($aprobados+$rechazados)?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>Gastos Aprobados</td>
					<td><?=$aprobados?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>Gastos Rechazados</td>
					<td><?=$rechazados?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><b>Total</b></td>
					<td><b style="font-size:12pt"><?="$ ".number_format($total_aprobado,0,",",".")?></b> / <?="$ ".number_format($total,0,",",".")?></td>
				</tr>
			</tbody>
		</table>
		<div class="row">
			Nota: <?=$model->nota?>
		</div>
	</div>
</div>

<style>
.verde{
	background:#dff0d8;
	color: green;
	padding: 5px;
}
.rojo{
	background:#f8d7da;
	color: #721c24;
	padding: 5px;
}
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