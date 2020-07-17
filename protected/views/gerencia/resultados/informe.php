<h3>Informe de Resultados</h3> 
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
<?php echo CHtml::link('Exportar Informe a Excel','exportarResultados'); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('resultados/_search',array(
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
		['name' => 'produccion', 'value' => '"$".number_format($data->produccion,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'repuesto', 'value' => '"$".number_format($data->repuesto,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'combustible', 'value' => '"$".number_format($data->combustible,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'resultado', 'value' => '"$".number_format($data->resultado,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
    ),
));