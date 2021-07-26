<?php
/* @var $this InformeGastoController */
/* @var $model InformeGasto */

$this->breadcrumbs=array(
	'Informe Gastos'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List InformeGasto', 'url'=>array('index')),
	array('label'=>'Create InformeGasto', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#informe-gasto-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Informe Gastos</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'informe-gasto-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'titulo',
		'numero',
		'fecha_envio',
		'fecha_cierre',
		'nombre_empleado',
		/*
		'rut_empleado',
		'aprobado_por',
		'politica_id',
		'politica',
		'estado',
		'total',
		'total_aprobado',
		'nro_gastos',
		'nro_gastos_aprobados',
		'nro_gastos_rechazados',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
