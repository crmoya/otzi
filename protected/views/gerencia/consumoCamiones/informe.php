<h3>Informe de Consumo de camiones, camionetas, autos</h3> 
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
<?php echo CHtml::link('Exportar Informe a Excel','exportarConsumoCamiones'); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('consumoCamiones/_search',array(
	'model'=>$model,
)); ?>
</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
	'id'=>'equipo-propio-grid',
	'columns'=>array(
        'maquina',     
		'operador',
		['name' => 'ltsFisicos', 'value' => 'number_format($data->ltsFisicos,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'kmsFisicos', 'value' => 'number_format($data->kmsFisicos,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'kmsGps', 'value' => 'number_format($data->kmsGps,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'consumoReal', 'value' => 'number_format($data->consumoReal,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'consumoGps', 'value' => 'number_format($data->consumoGps,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'consumoSugerido', 'value' => 'number_format($data->consumoSugerido,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		
    ),
));