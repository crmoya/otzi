

<?php

$this->breadcrumbs=array(
	'Infome de Gasto de Remuneraciones'=>array('admin'),
	'Detalle de informe de gastos de remuneraciones',
);

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/redmond/jquery-ui.css"/>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>


<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); 


?>
<h3><?=Yii::app()->getController()->pageTitle?></h3>
<p>
    <table>
        <?php
        if($fecha_inicio != "" || $fecha_fin != ""){
            echo "<tr>";
            echo "<td width='150px'><b>Período</b>: </td><td>";
            echo ($fecha_inicio != "")? Tools::backFecha($fecha_inicio) . " ":"";
            echo ($fecha_fin != "")?"hasta " . Tools::backFecha($fecha_fin):"";
            echo "</td></tr>";
        }
        ?>
        <?php
        if($propiosOArrendados != "" && $propiosOArrendados != "TODOS"){
            echo "<tr>";
            echo "<td width='150px'><b>Tipo Vehículo</b>: </td><td>";
            echo ($propiosOArrendados == "CP")?"Camiones Propios":"";
            echo ($propiosOArrendados == "CA")?"Camiones Arrendados":"";
            echo ($propiosOArrendados == "EP")?"Máquinas Propias":"";
            echo ($propiosOArrendados == "EA")?"Máquinas Arrendadas":"";
            echo ($propiosOArrendados == "E")?"Máquinas Propias y Arrendadas":"";
            echo ($propiosOArrendados == "C")?"Camiones Propios y Arrendadas":"";
            echo "</td></tr>";
        }
        ?>
        <?php
        if($maquina != ""){
            echo "<tr>";
            echo "<td width='150px'><b>Vehículo</b>: </td><td>";
            echo $maquina."</td></tr>";
        }
        ?>
        <?php
        if($operador != ""){
            echo "<tr>";
            echo "<td width='150px'><b>Operador</b>: </td><td>";
            echo $operador."</td></tr>";
        }
        ?>
        <?php
        if($centro_gestion != ""){
            echo "<tr>";
            echo "<td width='150px'><b>Faena</b>: </td><td>";
            echo $centro_gestion."</td></tr>";
        }
        ?>
    </table>
    
</p>
<table style="display:none;">
 <tr>
  
	<td>
  	<?php echo $form->label($model,'fecha_fin'); ?>
  	<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker',
			array(
				'model'=>$model,
				'language' => 'es',
				'attribute'=>'fecha_fin',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'yy-mm-dd',
					'changeYear'=>true,
					'changeMonth'=>true,
				),
				'htmlOptions'=>array(
					'style'=>'width:90px;',
				),
			)
		);
	?>
	</td>
 </tr>
</table>
<?php $this->endWidget(); ?>