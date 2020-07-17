<h3>Informe de Cargas de Combustible</h3> 
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
<?php echo CHtml::link('Exportar Informe a Excel','exportar'); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
	'id'=>'equipo-propio-grid',
	'columns'=>array(
        ['name' => 'petroleoLts', 'value' => 'number_format($data->petroleoLts,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'carguio', 'value' => 'number_format($data->carguio,2,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        ['name' => 'valorTotal', 'value' => '"$".number_format($data->valorTotal,0,",",".")', 'htmlOptions' => ['style' => 'text-align:right;']],
        'faena',
        'tipoCombustible',
        'numero',
        'nombre',
        array(            
            'name'=>'fechaRendicion',
            'value'=>array($model,'gridDataColumn'), 
        ),
        'camion',
        array(            
            'name'=>'tipo_documento',
            'value'=>'Tools::getTipoDocumentoComb($data->tipo_documento)', 
        ),
        'rut_proveedor',
        'nombre_proveedor',
        'factura',
        'reporte',
        array(            
            'name'=>'fecha',
            'value'=>'Tools::backFecha($data->fecha)', 
        ),
        'observaciones',
    ),
));
