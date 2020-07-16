<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); 


$f_inicio = date("01/m/Y");
$f_fin = date("d/m/Y");

if(isset($_GET['Informecombustible'])){
	$f_inicio = $_GET['Informecombustible']['fecha_inicio'];
	$f_fin = $_GET['Informecombustible']['fecha_fin'];
}

?>
<table>
		<tr>
			<td style="width:70px;"><?php echo $form->label($model,'fecha_inicio'); ?></td>
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
						        'style'=>'width:70px;',
								'readonly'=>'readonly',
								'class'=>'fecha_inicio',
								'value'=>$f_inicio,
						    ),
						)
					);
				?>	
				<br/><div id="errorFecha" class="errorMessage"></div>						
			</td>
			<td style="width:70px;"><?php echo $form->label($model,'fecha_fin'); ?></td>
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
					        'style'=>'width:70px;',
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
			<td style="width:70px;"><?php echo $form->label($model,'nombre'); ?></td>
			<td colspan="3"><?php echo $form->textField($model,'nombre');?></td>
		</tr>
		<tr>
			<td style="width:70px;"><?php echo $form->label($model,'numero'); ?></td>
			<td colspan="3"><?php echo $form->textField($model,'numero');?></td>
		</tr>
		<tr>
			<td style="width:70px;"><?php echo $form->label($model,'propio_arrendado'); ?></td>
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
			<td style='width:70px'><?php echo $form->label($model,'agrupar_por');?></td>
			<td>
				<?php 
					echo $form->dropDownList($model,'agrupar_por', 
						CHtml::listData(array(
							array('id'=>'','nombre'=>'Sin agrupación'),
							array('id'=>'fechaRendicion','nombre'=>'Fecha'),
							array('id'=>'numero','nombre'=>'Número'),
							array('id'=>'nombre','nombre'=>'Nombre de quien rinde'),
							array('id'=>'numero_fechaRendicion','nombre'=>'Fecha y Número'),
							array('id'=>'nombre_fechaRendicion','nombre'=>'Fecha y Nombre de quien rinde'),
							array('id'=>'nombre_numero_fechaRendicion','nombre'=>'Fecha, Número y Nombre de quien rinde'),
						), 'id', 'nombre'));
				?>				
			</td>
		</tr>
	</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->