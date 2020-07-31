<?php
/* @var $this GastoCompletaController */
/* @var $model GastoCompleta */

?>

<h1>Registros de gastos de <?=$gastoNombre?></h1>
<?php echo CHtml::link('Exportar a Excel','exportar'); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gasto-completa-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'proveedor',
		'fecha',
		'neto',
		'total',
		'categoria',
		'grupocategoria',
		'nota',
		'retenido',
		'cantidad',
		'centro_costo_faena',
		'departamento',
		'faena',
		'impuesto_especifico',
		'iva',
		'km_carguio',
		'litros_combustible',
		'monto_neto',
		'nombre_quien_rinde',
		'nro_documento',
		'periodo_planilla',
		'rut_proveedor',
		'supervisor_combustible',
		'tipo_documento',
		'unidad',
		'vehiculo_equipo',
		'vehiculo_oficina_central',
		[
			'class' => 'CLinkColumn',
			'header' => 'Imagen',
			'urlExpression' => '$data->imagen',
			'linkHtmlOptions'=>array('target'=>'_blank'),
			'imageUrl' => Yii::app()->request->baseUrl.'/images/search.png',
			'htmlOptions' => ['target'=>'_blank'],
		],
	),
)); ?>
