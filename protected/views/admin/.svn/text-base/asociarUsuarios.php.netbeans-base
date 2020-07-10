<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 

?>
<script language="javascript" type="text/javascript">
	$(function() {
		$("#guardar").click(function(){
			var valid = true;
			return valid;
		});

		$('#guardar').bind('keypress', false);

		var saved_users = Array();
		<?php 
			$usuarios_contratos = UsuariosContratos::model()->getUsers($contrato->id);
			$l = 0;
			foreach($usuarios_contratos as $usuario){
				$usuario_id = $usuario->usuarios_id;
				echo "saved_users[$l]=$usuario_id;";
				$l++;
			}
		?>		

		$(".select-on-check").each(function(e){
			if(contains(saved_users,$(this).val())){
				$(this).attr('checked',true);
			}
		});

	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Asociar Usuarios a Contrato: <?php echo CHtml::encode($contrato->nombre);?></h3>

<?php if(Yii::app()->user->hasFlash('asociarMessage')): ?>

<div class="flash-success">
<?php echo Yii::app()->user->getFlash('asociarMessage'); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('asociarError')): ?>

<div class="flash-error">
<?php echo Yii::app()->user->getFlash('asociarError'); ?>
</div>
<?php endif; ?>

<?php if(!Yii::app()->user->hasFlash('asociarMessage') && !Yii::app()->user->hasFlash('asociarError')): ?>

<div class="form" style="width:900px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asociar-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>

	<fieldset id="fs">
		<legend>Datos del Contrato</legend>
		<div>
			<span><b>Nombre:</b></span>
			<?php echo CHtml::encode($contrato->nombre); ?>
			<span>&nbsp;</span>
			<span><b>Fecha Inicio:</b></span>
			<?php echo CHtml::encode(Tools::backFecha($contrato->fecha_inicio)); ?>
			<br/>
			<span><b>Observaciones:</b></span>
			<?php echo CHtml::encode($contrato->observacion);?>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Asociar Usuarios al Contrato</legend>
		
		<?php
		  $this->widget('ext.selgridview.SelGridView', array(
		    'id' => 'users-grid',
		  	'filter'=>$usuarios,
		    'dataProvider' => $usuarios->searchChb(),
		    'selectableRows' => 2,
		    'columns'=>array(
		        array(
		          	'class' => 'CCheckBoxColumn',
		        	'checked' => '$data["id"]',
		        	'checkBoxHtmlOptions' => array(
		                'name' => 'chbUsuarioId[]',
		            ),
            		'value'=>'$data->id',
		        ),
		        'id',
		        'user',
		        'nombre',
		        'email',
		        'rol',
		     ),
		  ));
		?>
	<span class="note">Solo los usuarios que estén seleccionados quedarán asociados al contrato.</span>
	</fieldset>
	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>
	
</div>
<!-- form -->


