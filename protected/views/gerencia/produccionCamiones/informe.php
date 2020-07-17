<h3>Informe de Producción de camiones, camionetas, autos</h3> 
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
echo CHtml::link('Aplicar Filtros al Informe','#',array('class'=>'search-button')); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo CHtml::link('Exportar Informe a Excel','exportarProduccionCamiones'); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('produccionCamiones/_search',array(
	'model'=>$model,
)); ?>
</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
	'id'=>'equipo-propio-grid',
	'columns'=>array(
        'camion',     
        'chofer',
		'centroGestion',
		['name' => 'totalTransportado', 'value' => 'number_format($data->totalTransportado,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'produccion', 'value' => '"$".number_format($data->produccion,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'produccionReal', 'value' => '"$".number_format($data->produccionReal,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'diferencia', 'value' => '"$".number_format($data->diferencia,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
    ),
));