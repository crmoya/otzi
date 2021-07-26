<h3>Expediciones de Camiones, Camionetas y Autos</h3>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('equipo-propio-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
echo CHtml::link('Aplicar Filtros al Informe', '#', array('class' => 'search-button')); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo CHtml::link('Exportar Informe a Excel', 'exportar'); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search', array(
		'model' => $model,
	)); ?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $model->search(),
	'id' => 'equipo-propio-grid',
	'columns' => array(
		array(
			'name' => 'fecha',
			'value' => array($model, 'gridDataColumn'),
		),
		'vehiculo',
		'chofer',
		['name' => 'nVueltas', 'value' => 'number_format($data->nVueltas,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'totalTransportado', 'value' => 'number_format($data->totalTransportado,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'total', 'value' => '"$".number_format($data->total,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'kmRecorridos', 'value' => 'number_format($data->kmRecorridos,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		['name' => 'pu', 'value' => '"$".number_format($data->pu,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
		'faena',
		'origen_destino_nombre',
	),
));
