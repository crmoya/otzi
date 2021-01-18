<?php 

$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 

$f_inicio = date("01/m/Y");
$f_fin = date("d/m/Y");

if(isset($_GET['Expediciones'])){
	$f_inicio = $_GET['Expediciones']['fecha_inicio'];
	$f_fin = $_GET['Expediciones']['fecha_fin'];
}

?>
<script language="javascript" type="text/javascript">
	$(function() {
		var faena_id = $('#Expediciones_faena_id').val();
		if(faena_id != ''){
			$.ajax({
			  	type: "POST",
			  	url: "http://www.ctipaume.cl/ctipaume/index.php/faena/getOrigenesDestinos",
			  	data: { faena_id: faena_id}
			}).done(function( msg ) {
				$('#origenes').html(msg);
			});
		}

		$("#filtrar").click(function(){
			//var valid = checkCompareFechas();	
			return valid;
		});
	});

	
	
</script>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
<table>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'fecha_inicio'); ?></td>
			<td width="150">
				<?php 
					$this->widget('zii.widgets.jui.CJuiDatePicker',
						array(
							'model'=>$model,
							'language' => 'es',
							'attribute'=>'fecha_inicio',
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>'dd/mm/yy',
								'changeYear'=>true,
								'changeMonth'=>true,
							),
							'htmlOptions'=>array(
						        'style'=>'width:90px;',
								'readonly'=>'readonly',
								'class'=>'fecha_inicio',
								'value'=>$f_inicio,
						    ),
						)
					);
				?>	
				<br/><div id="errorFecha" class="errorMessage"></div>						
			</td>
			<td style="width:90px;"><?php echo $form->label($model,'fecha_fin'); ?></td>
			<td>
				<?php 
				$this->widget('zii.widgets.jui.CJuiDatePicker',
					array(
						'model'=>$model,
						'language' => 'es',
						'attribute'=>'fecha_fin',
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'dd/mm/yy',
							'changeYear'=>true,
							'changeMonth'=>true,
						),
						'htmlOptions'=>array(
					        'style'=>'width:90px;',
							'readonly'=>'readonly',
							'class'=>'fecha_final',
							'value'=>$f_fin,
					    ),
					)
				);
				?>
			</td>
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'chofer'); ?></td>
			<td colspan="3">
				<?php 
					echo $form->dropDownList(
						$model,
						'chofer',
						CHtml::listData(Chofer::model()->listarNombre(), 'id', 'nombre'),
						array(
							'class'=>'camion',
						)
					);
				?>				
			</td>			
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'faena'); ?></td>
			<td colspan="3">
			<?php 
				echo $form->dropDownList(
					$model,
					'faena_id',
					CHtml::listData(Faena::model()->listar(), 'id', 'nombre'),
					array(
						'class'=>'camion',
						'ajax' => array(
							'type'=>'POST', //request type
							'url'=>CController::createUrl('//faena/getOrigenesDestinos'), 
							'update'=>'#origenes', 
						)
					)
				);
 			?>
			</td>
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'origen_destino'); ?></td>
			<td colspan="3">
				<?php 
					echo $form->dropDownList(
						$model,
						'origen_destino', 
						CHtml::listData(array(array('id'=>'','nombre'=>'Seleccione Origen -> Destino')), 'id', 'nombre'),
						array('id'=>'origenes'));
				?>
			</td>
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'propio_arrendado'); ?></td>
			<td>
				<?php 
				echo $form->dropDownList($model,'propio_arrendado', 
					CHtml::listData(array(
						array('id'=>'','nombre'=>'Propios y Arrendados'),
						array('id'=>'P','nombre'=>'Propios'),
						array('id'=>'A','nombre'=>'Arrendados'),
					), 'id', 'nombre'));
				?>				
			</td>
			<td style='width:90px'><?php echo $form->label($model,'agrupar_por');?></td>
			<td>
				<?php 
					echo $form->dropDownList($model,'agrupar_por', 
						CHtml::listData(array(
							array('id'=>'','nombre'=>'Sin agrupación'),
							array('id'=>'fecha','nombre'=>'Fecha'),
							array('id'=>'vehiculo','nombre'=>'camión, camioneta, auto'),
							array('id'=>'chofer','nombre'=>'Chofer'),
							array('id'=>'fecha_vehiculo','nombre'=>'Fecha y Vehículo'),
							array('id'=>'fecha_chofer','nombre'=>'Fecha y Chofer'),
							array('id'=>'vehiculo_chofer','nombre'=>'Vehículo y Chofer'),
							array('id'=>'fecha_vehiculo_chofer','nombre'=>'Fecha, Vehículo y Chofer'),
						), 'id', 'nombre'));
				?>				
			</td>
		</tr>
	</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar',array('id'=>'filtrar')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->