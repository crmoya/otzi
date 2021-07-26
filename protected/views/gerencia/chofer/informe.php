<h3>Informe Cálculo de Trato Chofer</h3> 
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
<?php echo CHtml::link('Exportar Informe a Excel','exportarChofer'); ?>
<br/><br/>
<div class="search-form" style="display:none">
<?php $this->renderPartial('chofer/_search',array(
	'model'=>$model,
)); ?>
</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
	'id'=>'equipo-propio-grid',
	'columns'=>array(
		'chofer',
        'camion',
		['name' => 'produccionDia', 'value' => '"$".number_format($data->produccionDia,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'produccionMinima', 'value' => '"$".number_format($data->produccionMinima,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],
		['name' => 'coeficienteCombustible', 'value' => 'number_format($data->coeficienteCombustible,2,",",".")','htmlOptions'=>['style'=>'text-align:right;']],  
		['name' => 'gastoCombustible', 'value' => '"$".number_format($data->gastoCombustible,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],  
		['name' => 'diferencia', 'value' => '"$".number_format($data->diferencia,0,",",".")','htmlOptions'=>['style'=>'text-align:right;']],  
		
    ),
));