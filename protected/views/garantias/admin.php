<?php
/* @var $this GarantiasController */
/* @var $model Garantias */

$this->breadcrumbs=array(
	'Garantias'=>array('index'),
	'Administrar',
);

$this->menu=array(
	array('label'=>'Listar Garantias', 'url'=>array('index')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#garantias-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Administrar Garantías</h1>

<p>
Se pueden ingresar operadores de comparación (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) al comienzo de cada una de sus búsquedas para especificar cómo efectuar la comparación.
</p>

<?php echo CHtml::link('Búsqueda avanzada','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'garantias-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'numero',
		'monto',
		'fecha_vencimiento',
		'instituciones_id',
		'tipos_garantias_id',
		'contratos_id',
		'objetos_garantias_id',
		'modificador_id',
		'creador_id',
		'observacion',
		'tipo_monto',
		'estado_garantia',
		'fecha_devolucion',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
