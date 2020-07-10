<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.currency.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 

$prodReales = 0;

?>
<script language="javascript" type="text/javascript">
	$(function() {
		$("#guardar").click(function(){
			var valid = true;
			//valid = valid && checkProduccionesReales();
			return valid;
		});

		calculaSumaProd();
		calculaSumaCosto();

		$('.dinero').each(function() {
			var text = $(this).val();
			text = replaceAll(text,',','.');
			text = replaceAll(text,'.','');
			if(!isNaN(text)){
				var num = new Number(text);
				num = num.toFixed(0);
		   		$(this).val(num);
		   		$(this).val(formatThousands($(this).val(),'.'));
			}else{
				$(this).val(0);
			}
			
		});

	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Modificar Flujos Programados de <?php echo CHtml::encode($contrato->nombre);?></h3>

<?php if(Yii::app()->user->hasFlash('resolucionesMessage')): ?>

<div class="flash-success">
<?php echo Yii::app()->user->getFlash('resolucionesMessage'); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('resolucionesError')): ?>

<div class="flash-error">
<?php echo Yii::app()->user->getFlash('resolucionesError'); ?>
</div>
<?php endif; ?>

<?php if(!Yii::app()->user->hasFlash('resolucionesMessage') && !Yii::app()->user->hasFlash('resolucionesError')): ?>

<div class="form" style="width:900px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'flujos-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>
	<div>
		<b>Fecha Inicio del Contrato:</b> <?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?>
	</div>
	<table>
                <tr>
                    <td><?php echo $form->labelEx($contrato,'rut_mandante'); ?></td><td><?php echo CHtml::encode($contrato->rut_mandante);?></td>
                    <td><?php echo $form->labelEx($contrato,'nombre_mandante'); ?></td><td><?php echo CHtml::encode($contrato->nombre_mandante);?></td>
                </tr>
                <tr>
                        <td><?php echo $form->labelEx($contrato,'nombre'); ?></td><td><?php echo CHtml::encode($contrato->nombre);?></td>
                        <td><?php echo $form->labelEx($contrato,'fecha_inicio'); ?></td><td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
                </tr>

		<tr>
			<td><?php echo $form->labelEx($contrato,'fecha_inicio');?></td>
			<td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio));?></td>
			<td style="font-size:0.9em;"><b>Fecha Fin del Contrato</b></td>
			<td class="fecha_contrato"><?php echo CHtml::encode(Tools::backFecha($ultima_res->fecha_final));?></td>
		</tr>
		<?php 
			$tipoContrato = TiposContratos::model()->findByPk($contrato->tipos_contratos_id);
			$tipoReajuste = TiposReajustes::model()->findByPk($contrato->tipos_reajustes_id);
			if($tipoContrato != null && $tipoReajuste != null):
		?>
		<tr>
			<td><?php echo $form->labelEx($contrato,'tipos_contratos_id'); ?></td>
			<td><?php echo CHtml::encode($tipoContrato->nombre);?></td>
			<td><?php echo $form->labelEx($contrato,'tipos_reajustes_id'); ?></td>
			<td><?php echo CHtml::encode($tipoReajuste->nombre);?></td>
		</tr>
		<?php endif;?>
		<tr>
			<td><?php echo $form->labelEx($contrato,'codigo_safi');?></td>
			<td><?php echo CHtml::encode($contrato->codigo_safi);?></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<br/>
	<fieldset>
		<legend>Resoluciones del Contrato</legend>
		<?php 
		$r=0;
		$i = 0;
		$primera = true;
		foreach($resoluciones as $res):?>
		<table class="tableResolucion">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<div class="divResolucion">
									<b>N°Resolución:</b>
									<?php echo CHtml::encode($res->numero); ?>
									&nbsp;&nbsp;
									<b>Monto:</b>
									<span class="plata"><?php echo CHtml::encode($res->monto); ?></span>
									&nbsp;&nbsp;
									<?php if($primera):
									$primera = false;?>
									<b>Fecha Inicio:</b>
									<?php echo CHtml::encode(Tools::backFecha($res->fecha_inicio)); ?>
									<?php else:?>
									<b>Fecha Tramitada:</b>
									<?php echo CHtml::encode(Tools::backFecha($res->fecha_inicio)); ?>
									<?php endif;?>
									&nbsp;&nbsp;
									<b>Fecha Fin:</b>
									<?php echo CHtml::encode(Tools::backFecha($res->fecha_final)); ?>
									<input type="hidden" value="<?php echo CHtml::encode(Tools::backFecha($res->fecha_final)); ?>" class="fecha_min"/>
									&nbsp;&nbsp;
									<b>Generada por:</b>
									<?php 
									$usuario = Usuarios::model()->findByPk($res->creador_id);
									echo CHtml::encode($usuario->nombre); 
									?>
									&nbsp;&nbsp;
									<b>Modificada por:</b>
									<?php 
									$usuario = Usuarios::model()->findByPk($res->modificador_id);
									echo CHtml::encode($usuario->nombre); 
									?>
									<br/>
									<b>Observación: </b>
									<?php echo CHtml::encode($res->observacion); ?>
								</div>
							</td>
						</tr>
						<?php
						if(isset($flujosProgramados[$res->id])):
						foreach($flujosProgramados[$res->id] as $flujoP):
						?>
						<tr>
							<td>
								<fieldset class="flujoReal3">
									<legend><?php echo CHtml::encode(Tools::getMes($flujoP->mes)); ?> <?php echo CHtml::encode($flujoP->agno); ?></legend>
									<table>
										<tr>
										  <td><?php echo $form->labelEx($flujoP,"produccion");?></td>
										  <td><?php echo $form->textField($flujoP,"[$i]produccion",array('size'=>9,'class'=>'dinero produccion','id'=>"produccion$i"));?></td>
										  <td><?php echo $form->labelEx($flujoP,"costo");?></td>
										  <td><?php echo $form->textField($flujoP,"[$i]costo",array('size'=>9,'class'=>'dinero costo','id'=>"costo$i"));?>
										  	 <?php echo $form->hiddenField($flujoP,"[$i]id");?>
										  </td>
										</tr>
										<tr> 
										  <td><?php echo $form->labelEx($flujoP,"comentarios");?></td>
										  <td colspan="3"><?php echo $form->textField($flujoP,"[$i]comentarios",array('class'=>'upper','size'=>100));?></td>														  
										</tr>
										<tr>
										  <td style="font-size:0.9em;"><b>Producción Programada Acumulada Neta</b></td>
										  <td id="prodAcum<?php echo $i;?>"></td>
										  <td style="font-size:0.9em;"><b>Costo Programado Acumulado Neto</b></td>
										  <td id="costoAcum<?php echo $i;?>"></td>
										</tr>							
									</table>
									
								</fieldset>									
							</td>
						</tr>
						<?php 
						$i++;
						endforeach;
						endif;?>
					</table>
				</td>
			</tr>
		</table>
		<?php 
		$r++;
		endforeach;?>
	</fieldset>
			

	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->


