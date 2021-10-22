<?php


$this->menu=array(
	array('label'=>'Crear Faena', 'url'=>array('createv')),
	array('label'=>'Editar Faena', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Faena', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Faenas', 'url'=>array('adminv')),
);

?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo CHtml::link('Exportar Faena a Excel','export/'.$model->id); ?>
<h1>Ver Faena #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		['name'=>'combustible','value'=>$model->combustible==1?"SÍ":"NO"],
		'vigente',
	),
)); ?>
<br/>
<h3>PU's por volumen de la Faena:</h3>
<table>
	<thead>
		<tr>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Origen
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Destino
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				PU
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				KMs
			</th>
		</tr>
	</thead>	
	<tbody>
	<?php 
        foreach ($ods as $od) {                
			$origen = ($od->origen!=null)?$od->origen->nombre:"&nbsp;";
			$destino = ($od->destino!=null)?$od->destino->nombre:"&nbsp;";
			echo "
				<tr>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$origen."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$destino."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$od['pu']."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$od['kmRecorridos']."</td>
				</tr>";
                
	}
	?>
	</tbody>

</table>

<h3>PU's por tiempo de la Faena:</h3>
<table>
	<thead>
		<tr>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Camión o Camioneta
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Unidad
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				PU
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Observaciones
			</th>
		</tr>
	</thead>	
	<tbody>
	<?php 
        foreach ($us as $u) {                
			$unidad = Unidadfaena::getUnidad($u->unidad);
			$camion_propio = CamionPropio::model()->findByPk($u->camionpropio_id);
			$camion_arrendado = CamionArrendado::model()->findByPk($u->camionarrendado_id);
			$camion = "";
			if(isset($camion_propio)){
				$camion = $camion_propio->nombre." (Propio)";
			}
			if(isset($camion_arrendado)){
				$camion = $camion_arrendado->nombre." (Arrendado)";
			}
			echo "
				<tr>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$camion."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$unidad."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$u['pu']."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$u['observaciones']."</td>
				</tr>";
                
	}
	?>
	</tbody>

</table>

<table>
	<thead>
		<tr>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Equipo
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Unidad
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				PU
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Horas Mínimas
			</th>
			<th style='background:#e5f1f4;border:white 1px solid;'>
				Observaciones
			</th>
		</tr>
	</thead>	
	<tbody>
	<?php 
        foreach ($use as $u) {                
			$unidad = UnidadfaenaEquipo::getUnidad($u->unidad);
			$equipo_propio = EquipoPropio::model()->findByPk($u->equipopropio_id);
			$equipo_arrendado = EquipoArrendado::model()->findByPk($u->equipoarrendado_id);
			$equipo = "";
			if(isset($equipo_propio)){
				$equipo = $equipo_propio->nombre." (Propio)";
			}
			if(isset($equipo_arrendado)){
				$equipo = $equipo_arrendado->nombre." (Arrendado)";
			}
			echo "
				<tr>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$equipo."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$unidad."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$u['pu']."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$u['horas_minimas']."</td>
					<td style='background:#f8f8f8;border:white 1px solid;'>".$u['observaciones']."</td>
				</tr>";
                
	}
	?>
	</tbody>

</table>