<?php 

$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 


$f_inicio = date("01/m/Y");
$f_fin = date("d/m/Y");

if(isset($_GET['Observaciones'])){
	$f_inicio = $_GET['Observaciones']['fecha_inicio'];
	$f_fin = $_GET['Observaciones']['fecha_fin'];
}

?>
<script language="javascript" type="text/javascript">
	$(function() {

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
			<td>
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
			<td style="width:90px;"><?php echo $form->label($model,'obra_maquina'); ?></td>
			<td>
				<?php 
				echo $form->dropDownList($model,'obra_maquina', 
					CHtml::listData(array(
						array('id'=>'','nombre'=>'Todas'),
						array('id'=>'M','nombre'=>'Contienen observaciones de Máquina'),
						array('id'=>'O','nombre'=>'Contienen observaciones de Obra'),
					), 'id', 'nombre'));
				?>				
			</td>		
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'maquina_camion'); ?></td>
			<td>
				<?php 
				echo $form->dropDownList($model,'maquina_camion', 
					CHtml::listData(array(
						array('id'=>'','nombre'=>'Máquinas y Vehículos'),
						array('id'=>'M','nombre'=>'Sólo Máquinas'),
						array('id'=>'V','nombre'=>'Sólo Vehículos'),
					), 'id', 'nombre'));
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
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'faena'); ?></td>
			<td colspan="3">
			<?php 
				echo $form->dropDownList(
					$model,
					'faena_id',
					CHtml::listData(Faena::model()->listar(), 'id', 'nombre')
				);
 			?>
			</td>
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'maquina'); ?></td>
			<td>
				<?php 
					
					echo $form->dropDownList(
						$model,
						'maquina_id',
						CHtml::listData(Observaciones::model()->listarMaquinas(), 'id', 'nombre'),
						array(
							'class'=>'camion',
						)
					);
				?>				
			</td>
		</tr>
		<tr>
			<td style="width:90px;"><?php echo $form->label($model,'chofer'); ?></td>
			<td>
				<?php 
					
					echo $form->dropDownList(
						$model,
						'chofer_id',
						CHtml::listData(Observaciones::model()->listarChoferes(), 'id', 'nombre'),
						array(
							'class'=>'camion',
						)
					);
				?>				
			</td>
		</tr>
	</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar',array('id'=>'filtrar')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->