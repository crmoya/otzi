<h3>Informe de Producción de Maquinaria</h3> 
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
<?php echo CHtml::link('Exportar Informe a Excel','exportarProduccionMaquinaria'); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('produccionMaquinaria/_search',array(
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
		'centroGestion',
		['name' => 'pu', 'value' => '"$".number_format($data->pu,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'horas', 'value' => 'number_format($data->horas,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'horasMin', 'value' => 'number_format($data->horasMin,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'produccion', 'value' => '"$".number_format($data->produccion,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'produccionMin', 'value' => '"$".number_format($data->produccionMin,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
    ),
));