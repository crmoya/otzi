<?php

$this->menu=array(
	array('label'=>'Crear Centro de Gestión', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('centro-gestion-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Administrar Centros de Gestión</h1>


<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'centro-gestion-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'nombre',
		'vigente',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
