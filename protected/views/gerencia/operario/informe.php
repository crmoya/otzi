<h3>Informe Cálculo de Trato Operador</h3> 
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
<?php echo CHtml::link('Exportar Informe a Excel','exportarOperario'); ?>
<br/><br/>
<div class="search-form" style="display:none">
<?php $this->renderPartial('operario/_search',array(
	'model'=>$model,
)); ?>
</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
	'id'=>'equipo-propio-grid',
	'columns'=>array(
        'operario',
		'maquina',
		['name' => 'consumoPromedio', 'value' => '"$".number_format($data->consumoPromedio,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'coeficiente', 'value' => 'number_format($data->coeficiente,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'horas', 'value' => 'number_format($data->horas,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],  
		['name' => 'horasContratadas', 'value' => 'number_format($data->horasContratadas,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],  
		['name' => 'valorHora', 'value' => '"$".number_format($data->valorHora,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],  
		['name' => 'total', 'value' => '"$".number_format($data->total,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
    ),
));