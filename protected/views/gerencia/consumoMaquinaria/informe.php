<h3>Informe de Consumo de Maquinaria</h3> 
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
<?php echo CHtml::link('Exportar Informe a Excel','exportarConsumoMaquinaria'); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('consumoMaquinaria/_search',array(
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
		['name' => 'hrsFisicas', 'value' => 'number_format($data->hrsFisicas,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'hrsGps', 'value' => 'number_format($data->hrsGps,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'consumoEsperado', 'value' => 'number_format($data->consumoEsperado,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'consumo', 'value' => 'number_format($data->consumo,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'consumoGps', 'value' => 'number_format($data->consumoGps,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
    ),
));