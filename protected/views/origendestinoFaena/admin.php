<?php
$this->breadcrumbs=array(
	'Origendestino Faenas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List OrigendestinoFaena', 'url'=>array('index')),
	array('label'=>'Create OrigendestinoFaena', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('origendestino-faena-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Origendestino Faenas</h1>

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
	'id'=>'origendestino-faena-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('name'=>'origen_id', 'value'=>'$data->origen->nombre'),
		array('name'=>'destino_id', 'value'=>'$data->destino->nombre'),
		array('name'=>'faena_id', 'value'=>'$data->faena->nombre'),
		'pu',
		'id',
		array(
			'class'=>'CButtonColumn',
		),
	),
));

?>


