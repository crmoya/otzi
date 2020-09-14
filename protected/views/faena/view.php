<?php


$this->menu=array(
	array('label'=>'Crear Faena (medida por volumen)', 'url'=>array('createv')),
	array('label'=>'Editar Faena (medida por volumen)', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Faena (medida por volumen)', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Faenas (medidas por volumen)', 'url'=>array('adminv')),
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
		'vigente',
	),
)); ?>
<br/>
<h3>Orígenes / Destinos de la Faena:</h3>
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