<?php

$this->menu=array(
	array('label'=>'Crear Faena', 'url'=>array('createv')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('faena-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Administrar Faenas</h1>
<?php echo CHtml::link('Búsqueda Avanzada','#',array('class'=>'search-button')); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo CHtml::link('Exportar a Excel','exportar'); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'faena-grid',
	'dataProvider'=>$model->search(0),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'nombre',
		[
			'name'=>'combustible',
			'value'=>'$data->combustible==1?"SÍ":"NO"',
			'filter'=>CHtml::listData([['id'=>1,'name'=>'SÍ'],['id'=>0,'name'=>'NO'],], 'id', 'name'),
		],
		'vigente',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{delete}{update}{down}{up}',
			"buttons" => [
				'down' => [
					'label'=>'<i class="fa fa-ban text-danger"></i>',
					'url'=> '"updateNoVigente?id=$data->id"',
					'visible'=>'$data->vigente == "SÍ"',
					'click'=>'function(e){
						
					}',
				],
				'up' => [
					'label'=>'<i class="fa fa-ban text-success"></i>',
					'url'=> '"updateSiVigente?id=$data->id"',
					'visible'=>'$data->vigente == "NO"',
					'click'=>'function(e){
						
					}',
				],
			]
		),
	),
)); ?>
