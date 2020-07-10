<?php
/* @var $this EpCanjeRetencionController */
/* @var $model EpCanjeRetencion */

$this->breadcrumbs=array(
	'Ep Canje Retencions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List EpCanjeRetencion', 'url'=>array('index')),
	array('label'=>'Create EpCanjeRetencion', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ep-canje-retencion-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Ep Canje Retencions</h1>

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
	'id'=>'ep-canje-retencion-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'valor',
		'comentarios',
		'resoluciones_id',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
