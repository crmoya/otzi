<?php
$this->menu=array(
	array('label'=>'Crear camión, camioneta, auto Arrendado', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('camion-arrendado-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Administrar camiones, camionetas, autos Arrendados</h1>

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
	'id'=>'camion-arrendado-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'nombre',
		'capacidad',
		'consumoPromedio',
		'produccionMinima',
		'horasMin',
		'coeficienteDeTrato',
		 array(            
            'name'=>'pesoOVolumen',
            'value'=>array($model,'gridDataColumn'),
		),
		[
			'name'=>'odometro_en_millas',
			'value'=>'$data->odometro_en_millas==1?"SÍ":"NO"',
			'filter'=>CHtml::dropDownList('CamionArrendado[odometro_en_millas]', $model->odometro_en_millas, [''=>'TODOS','1'=>'SÍ','0'=>'NO']),
		],
        'vigente',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
