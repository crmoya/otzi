<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery'); 


?>
<script language="javascript" type="text/javascript">
	$(function() {
            
            var proveedores = Array();
                proveedores = [<?php
                    $resp = "";
                    $proveedores = Proveedor::model()->findAll();
                    foreach($proveedores as $proveedor)
                        $resp .= "'".$proveedor->rut."',";
                    if(count($proveedores)>0){
                        $resp = substr($resp, 0,  strlen($resp)-1);
                    }
                    echo $resp;
                ?>];
                $(document.body).on('focus','.rut_proveedor',function(e){
                    var i = $(this).attr('i');
                    $(this).autocomplete({
                        source: proveedores,
                        select: function(ev,ui){
                            var rut = ui.item.value;
                            $.ajax({
                                type: "POST",
                                url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/');?>",
                                data: { rut: rut }
                            }).done(function( msg ) {
                                var disponibles = msg.split("|");
                                if(disponibles.length > 0){
                                   $('#nombre_proveedor'+i).val(disponibles[0]);
                                }
                            });
                        }
                    });
                    if(proveedores.length > 0){
                       $('#rut_proveedor'+i).data("ui-Autocomplete").search(' ');
                    }
                });
                
                $(document.body).on('focus','.rut_proveedorR',function(e){
                    var i = $(this).attr('i');
                    $(this).autocomplete({
                        source: proveedores,
                        select: function(ev,ui){
                            var rut = ui.item.value;
                            $.ajax({
                                type: "POST",
                                url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/');?>",
                                data: { rut: rut }
                            }).done(function( msg ) {
                                var disponibles = msg.split("|");
                                if(disponibles.length > 0){
                                   $('#nombre_proveedorR'+i).val(disponibles[0]);
                                }
                            });
                        }
                    });
                    if(proveedores.length > 0){
                       $('#rut_proveedorR'+i).data("ui-Autocomplete").search(' ');
                    }
                });
                
                $(document.body).on('keyup','.rut_proveedor',function(e){
                    var rut = $(this).val();
                    var i = $(this).attr('i');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/');?>",
                        data: { rut: rut }
                    }).done(function( msg ) {
                        var disponibles = msg.split("|");
                        $('#nombre_proveedor'+i).autocomplete({
                            source: disponibles,
                            select: function(ev,ui){
                                var nombre = ui.item.value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRutExacto/');?>",
                                    data: { nombre: nombre }
                                }).done(function( msg ) {
                                    var disponibles = msg.split("|");
                                    if(disponibles.length > 0){
                                       $('#rut_proveedor'+i).val(disponibles[0]);
                                    }
                                });
                            }
                        });
                        if(disponibles.length > 0){
                            $('#nombre_proveedor'+i).data("ui-Autocomplete").search(' ');
                        }
                    });
		});
                $(document.body).on('keyup','.nombre_proveedorR',function(e){
                    var nombre = $(this).val();
                    var i = $(this).attr('i');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRut/');?>",
                        data: { nombre: nombre }
                    }).done(function( msg ) {
                        var disponibles = msg.split("|");
                        $('#rut_proveedorR'+i).autocomplete({
                            source: disponibles,
                            select: function(ev,ui){
                                var rut = ui.item.value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/');?>",
                                    data: { rut: rut }
                                }).done(function( msg ) {
                                    var disponibles = msg.split("|");
                                    if(disponibles.length > 0){
                                       $('#nombre_proveedorR'+i).val(disponibles[0]);
                                    }
                                });
                            }
                        });
                        if(disponibles.length > 0){
                           $('#rut_proveedorR'+i).data("ui-Autocomplete").search(' ');
                        }
                    });
		});
                
                
		$(document.body).on('keyup','.rut_proveedor',function(e){
                    var rut = $(this).val();
                    var i = $(this).attr('i');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/');?>",
                        data: { rut: rut }
                    }).done(function( msg ) {
                        var disponibles = msg.split("|");
                        $('#nombre_proveedor'+i).autocomplete({
                            source: disponibles,
                            select: function(ev,ui){
                                var nombre = ui.item.value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRutExacto/');?>",
                                    data: { nombre: nombre }
                                }).done(function( msg ) {
                                    var disponibles = msg.split("|");
                                    if(disponibles.length > 0){
                                       $('#rut_proveedor'+i).val(disponibles[0]);
                                    }
                                });
                            }
                        });
                        if(disponibles.length > 0){
                            $('#nombre_proveedor'+i).data("ui-Autocomplete").search(' ');
                        }
                    });
		});
                $(document.body).on('keyup','.nombre_proveedor',function(e){
                    var nombre = $(this).val();
                    var i = $(this).attr('i');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRut/');?>",
                        data: { nombre: nombre }
                    }).done(function( msg ) {
                        var disponibles = msg.split("|");
                        $('#rut_proveedor'+i).autocomplete({
                            source: disponibles,
                            select: function(ev,ui){
                                var rut = ui.item.value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/');?>",
                                    data: { rut: rut }
                                }).done(function( msg ) {
                                    var disponibles = msg.split("|");
                                    if(disponibles.length > 0){
                                       $('#nombre_proveedor'+i).val(disponibles[0]);
                                    }
                                });
                            }
                        });
                        if(disponibles.length > 0){
                           $('#rut_proveedor'+i).data("ui-Autocomplete").search(' ');
                        }
                    });
		});
		
		
            $(document.body).on('keyup','.rut_rinde',function(e){
                    var rut = $(this).val();
                    var i = $(this).attr('i');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/rendidor/');?>",
                        data: { rut: rut }
                    }).done(function( msg ) {
                        var disponibles = msg.split("|");
                        $('#nombre'+i).autocomplete({
                            source: disponibles,
                            select: function(ev,ui){
                                var nombre = ui.item.value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo Yii::app()->createUrl('//operativo/rendidorRutExacto/');?>",
                                    data: { nombre: nombre }
                                }).done(function( msg ) {
                                    var disponibles = msg.split("|");
                                    if(disponibles.length > 0){
                                       $('#rut_rinde'+i).val(disponibles[0]);
                                    }
                                });
                            }
                        });
                        if(disponibles.length > 0){
                           $('#nombre'+i).data("ui-Autocomplete").search(' ');
                        }
                    });
		});
                
                $(document.body).on('keyup','.nombre',function(e){
                    var nombre = $(this).val();
                    var i = $(this).attr('i');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/rendidorRut/');?>",
                        data: { nombre: nombre }
                    }).done(function( msg ) {
                        var disponibles = msg.split("|");
                        $('#rut_rinde'+i).autocomplete({
                            source: disponibles,
                            select: function(ev,ui){
                                var rut = ui.item.value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo Yii::app()->createUrl('//operativo/rendidor/');?>",
                                    data: { rut: rut }
                                }).done(function( msg ) {
                                    var disponibles = msg.split("|");
                                    if(disponibles.length > 0){
                                       $('#nombre'+i).val(disponibles[0]);
                                    }
                                });
                            }
                        });
                        if(disponibles.length > 0){
                           $('#rut_rinde'+i).data("ui-Autocomplete").search(' ');
                        }
                    });
		});
		
                
                var checkReporte = true;
                $('#REquipoArrendado_reporte').change(function(e){
                    var numero = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/validaReporteUnico/');?>",
                        data: { report: numero }
                    }).done(function( msg ) {
                        if(msg == '1'){
                           checkReporte = false;
                           $('#REquipoArrendado_reporte').css('background','pink');
                           alert('ERROR: Reporte ya existe.');
                        }
                        else{
                            checkReporte = true;
                            $('#REquipoArrendado_reporte').css('background','white');
                        }
                    });
		});
                
                

                $(document.body).on('keyup','.rut_rindeR',function(e){
		    var rut = $(this).val();
                    var i = $(this).attr('i');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('//operativo/rendidor/');?>",
                        data: { rut: rut }
                    }).done(function( msg ) {
                        var disponibles = msg.split("|");
                        $('#nombreR'+i).autocomplete({
                            source: disponibles,
                            select: function(ev,ui){
                                var nombre = ui.item.value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo Yii::app()->createUrl('//operativo/rendidorRutExacto/');?>",
                                    data: { nombre: nombre }
                                }).done(function( msg ) {
                                    var disponibles = msg.split("|");
                                    if(disponibles.length > 0){
                                       $('#rut_rindeR'+i).val(disponibles[0]);
                                    }
                                });
                            }
                        });
                        if(disponibles.length > 0){
                           $('#nombreR'+i).data("ui-Autocomplete").search(' ');
                        }
                    });
		});

		
		$("#guardar").click(function(){
			var valid = true;
			valid = valid && checkNVueltas();
                        valid = valid && checkReporte;
			valid = valid && checkChofer();
			valid = valid && checkFaena();
			valid = valid && checkHInicial();
			valid = valid && checkHFinal();
			valid = valid && checkTotal();
			valid = valid && checkTotalTransportado();

			valid = valid && checkFaenaComb();
			valid = valid && checkPetroleoLts();
			valid = valid && checkKmCarguio();
			valid = valid && checkPrecioUnitario();
			valid = valid && checkValorTotal();
			valid = valid && checkTipoCombustible();
			valid = valid && checkSupervisor();
			valid = valid && checkGuia();
                        valid = valid && checkNombreProveedor();
                        valid = valid && checkRutProveedor();

			valid = valid && checkRepuesto();
			valid = valid && checkCantidad();
			valid = valid && checkFaenaRep();
			valid = valid && checkMontoNeto();
			valid = valid && checkHorasArrendado();
			valid = valid && checkNumero();

			valid = valid && checkPanne();

			
			return valid;
		});
			
	});
</script>

<?php $this->pageTitle=Yii::app()->name; ?>
<h3>Ingreso de registro Equipos Arrendados:</h3>

<?php if(Yii::app()->user->hasFlash('equiposMessage')): ?>

<div class="flash-success">
<?php echo Yii::app()->user->getFlash('equiposMessage'); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('equiposError')): ?>

<div class="flash-error">
<?php echo Yii::app()->user->getFlash('equiposError'); ?>
</div>
<?php endif; ?>

<?php if(!Yii::app()->user->hasFlash('equiposMessage') && !Yii::app()->user->hasFlash('equiposError')): ?>

<div class="form" style="width:900px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equiposArrendados-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
),
)); ?>

	<p class="note" id="note">
		Campos con <span class="required">*</span> son requeridos.
	</p>

	<fieldset>
		<legend>Reporte de Equipo Arrendado</legend>
		<table>
			<tr>
				<td>
					<?php echo $form->labelEx($model,'fecha'); ?>
				</td>
				<td>
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',
						array(
							'model'=>$model,
							'language' => 'es',
							'attribute'=>'fecha',
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>'dd/mm/yy',
								'changeYear'=>true,
								'changeMonth'=>true,
							),
							'htmlOptions'=>array(
						        'style'=>'width:70px;',			
								'value'=>date("d/m/Y"),
						    ),
						)
					);
					?>				
					<?php echo $form->error($model,'fecha'); ?>
				</td>
				<td width="100"><?php echo $form->labelEx($model,'equipoArrendado_id'); ?>
				</td>
				<td><?php 
				echo $form->dropDownList(
				$model,
				'equipoArrendado_id',
				CHtml::listData(EquipoArrendado::model()->listar(), 'id', 'nombre'),
				array(
				'class'=>'equipo',
				'ajax' => array(
				'type'=>'POST', //request type
				'url'=>CController::createUrl('//operativo/llenaEquipo'), 
				'update'=>'#equipo', 
				)));
				?> <?php echo $form->error($model,'equipoArrendado_id'); ?>
				</td>
			</tr>
			<tr>
				<td width="30"><?php echo $form->labelEx($model,'ordenCompra'); ?></td>
				<td><?php echo $form->textField($model,'ordenCompra'); ?><?php echo $form->error($model,'ordenCompra'); ?></td>
				<td style='font-size: 0.9em;'><b>Propietario:</b></td>
				<td id="equipo"></td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend>Datos de la Expedición</legend>
		<table>
			<tr> 
			  <td width="30"><?php echo $form->labelEx($model,'reporte'); ?></td><td><?php echo $form->textField($model,'reporte'); ?><?php echo $form->error($model,'reporte'); ?></td>
			  <td width="30"><?php echo $form->labelEx($model,'operador_id'); ?></td>
			  <td>
			    <?php echo $form->dropDownList($model,'operador_id', CHtml::listData(Operador::model()->listar(), 'id', 'nombre')); ?>
			  	<?php echo $form->error($model,'operador_id'); ?>
			  </td>
			 </tr>
			 <tr>
			  <td width="30"><?php echo $form->labelEx($model,'hInicial'); ?></td><td><?php echo $form->textField($model,'hInicial',array('id'=>"hInicial",'class'=>'fixedHInicial')); ?><?php echo $form->error($model,'hInicial'); ?></td>
			  <td width="30"><?php echo $form->labelEx($model,'hFinal'); ?></td><td><?php echo $form->textField($model,'hFinal',array('id'=>"hFinal",'class'=>'fixedHFinal')); ?><?php echo $form->error($model,'hFinal'); ?></td>
			  <td width="30"><?php echo $form->labelEx($model,'horas'); ?></td><td><?php echo $form->textField($model,'horas',array('class'=>'fixed','style'=>'border:none;background:white;','readonly'=>'readonly')); ?><?php echo $form->error($model,'horas'); ?></td>
		 	</tr>	
		 	<tr>
		 	  <td width="30"><?php echo $form->labelEx($model,'horasGps'); ?></td><td><?php echo $form->textField($model,'horasGps',array('class'=>'fixed')); ?><?php echo $form->error($model,'horas'); ?></td>
			  <td width="30"><?php echo $form->labelEx($model,'faena_id'); ?></td>
			  <td colspan="3">
			   <?php echo $form->dropDownList($model,'faena_id', CHtml::listData(Faena::model()->listar(), 'id', 'nombre')); ?>
			   <?php echo $form->error($model,'faena_id'); ?>
			  </td>
			</tr>
		 </table>
	</fieldset>
	<fieldset id="cargaComb">
		<legend>Datos de carga de combustible</legend>
		<div class="complex">
			<table>
				<tr>
					<td style="vertical-align:top;">
						<div>
							<table class="templateFrame grid" cellspacing="0">
								<tbody class="templateTarget">
									
								</tbody>
								<tfoot>
									<tr>
										<td>
											<div class="add" tipo="combustible">Agregar</div>
											<textarea class="template" rows="0" cols="0">
												<tr class="templateContent">
													<td width="100px">
														<?php $combustible = new CargaCombEquipoArrendado();?>
														<table style="border:solid 1px silver;padding:10px;">
															
															<tr>
															  <td><?php echo $form->labelEx($combustible,"faena_id",array('style'=>'width:80px;'));?></td>
															  <td><?php echo $form->dropDownList($combustible,'[{0}]faena_id',CHtml::listData(Faena::model()->listar(), 'id', 'nombre'),array('id'=>'faenaC_id{0}'));?></td>
															  <td><div id="errorFaenaC_id{0}" style="color:red;width:100px;"></div></td>
															  <td></td>	
															  <td></td>	
															  <td></td>	
															  <td>
															  	<input type="hidden" class="rowIndex" value="{0}" />
															   	<div class="remove" tipo="combustible" id="removeCombustible{0}" validate="true">Eliminar</div>
															  </td>															  
															</tr>
                                                                                                                        
															<tr>
															 <td><?php echo $form->labelEx($combustible,"tipo_documento",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->dropDownList($combustible,'[{0}]tipo_documento',CHtml::listData(Tools::listarTiposDocumentosComb() ,'id', 'nombre'));?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible,"factura",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]factura",array('id'=>"factura{0}",'class'=>"factura",'i'=>'{0}'));?></td>
															 <td><div id="errorFactura{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
                                                                                                                        
                                                                                                                        <tr>
															 <td><?php echo $form->labelEx($combustible,"rut_proveedor",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]rut_proveedor",array('id'=>"rut_proveedor{0}",'class'=>'rut_proveedor','i'=>'{0}'));?></td>
															 <td><div id="errorRutProveedor{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($combustible,"nombre_proveedor",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]nombre_proveedor",array('id'=>"nombre_proveedor{0}",'class'=>'nombre_proveedor','i'=>'{0}'));?></td>
															 <td><div id="errorNombreProveedor{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($combustible,"petroleoLts",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]petroleoLts",array('id'=>"petroleoLts{0}",'class'=>'fixedPetroleoLts'));?></td>
															 <td><div id="errorPetroleoLts{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($combustible,"hCarguio",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]hCarguio",array('id'=>"kmCarguio{0}",'class'=>'fixed'));?></td>
															 <td><div id="errorKmCarguio{0}" style="color:red;width:100px;"></div></td>
															 
															 <td></td>
															</tr>
                                                                                                                        															
															<tr>
															 <td><?php echo $form->labelEx($combustible,"precioUnitario",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]precioUnitario",array('id'=>"precioUnitario{0}",'class'=>'fixedPrecio'));?></td>
															 <td><div id="errorPrecioUnitario{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($combustible,"valorTotal",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]valorTotal",array('id'=>"valorTotal{0}",'class'=>'fixedValor'));?></td>
															 <td><div id="errorValorTotal{0}" style="color:red;width:100px;"></div></td>
															 
															 <td></td>
															</tr>
															
															<tr>
															  <td><?php echo $form->labelEx($combustible,"tipoCombustible_id",array('style'=>'width:80px;'));?></td>
                                                                                                                          <td><?php echo $form->dropDownList($combustible,'[{0}]tipoCombustible_id',CHtml::listData(TipoCombustible::model()->findAll(), 'id', 'nombre'),array('id'=>'tipoCombustible{0}'));?></td>
															  <td><div id="errorTipoCombustible{0}" style="color:red;width:100px;"></div></td>
															  
															  <td><?php echo $form->labelEx($combustible,"supervisorCombustible_id",array('style'=>'width:80px;'));?></td>
															  <td><?php echo $form->dropDownList($combustible,'[{0}]supervisorCombustible_id',CHtml::listData(SupervisorCombustible::model()->listar(), 'id', 'nombre'),array('id'=>'supervisor{0}'));?></td>
															  <td><div id="errorSupervisor{0}" style="color:red;width:100px;"></div></td>	
															  <td>
															  </td>															  
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($combustible,"numero",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]numero",array('i'=>'{0}','class'=>'nroRendicion','id'=>"numero{0}"));?><div id="errorNumero{0}" class="errorMessage errorNumero"></div></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible,"rut_rinde");?></td>
															 <td><?php echo $form->textField($combustible,'[{0}]rut_rinde',array('style'=>'width:80px;','class'=>'rut_rinde','id'=>'rut_rinde{0}','i'=>'{0}'));?> </td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($combustible,"fechaRendicion",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]fechaRendicion",array('id'=>"fechaRendicion{0}",'class'=>'fecha','readonly'=>'readonly'));?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible,"nombre",array('style'=>'width:80px;'));?></td>
                                                                                                                         <td><?php echo $form->textField($combustible,'[{0}]nombre',array('i'=>'{0}','class'=>'nombre','id'=>'nombre{0}'));?> </td>
															 <td></td>
															 <td></td>
															</tr>
                                                                                                                        
                                                                                                                        <tr>
															 <td><?php echo $form->labelEx($combustible,"observaciones",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]observaciones",array('id'=>"observaciones{0}"));?><div id="errorObservaciones{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible,"guia",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($combustible,"[{0}]guia",array('id'=>"guia{0}"));?><div id="errorGuia{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															 <td></td>
															</tr>
														</table>	
													</td>
												</tr>
											</textarea>
										</td>
									</tr>
								</tfoot>
							</table>
						</div><!--panel-->
					</td>
				</tr>
			</table>
		</div><!--complex-->
	</fieldset>
	
	<fieldset id="cargaRep">
		<legend>Datos de compra de repuesto</legend>
		<div class="complex">
			<table>
				<tr>
					<td style="vertical-align:top;">
						<div>
							<table class="templateFrame grid" cellspacing="0">
								<tbody class="templateTarget">
									
								</tbody>
								<tfoot>
									<tr>
										<td>
											<div class="add" tipo="repuesto">Agregar</div>
											<textarea class="template" rows="0" cols="0">
												<tr class="templateContent">
													<td width="100px">
														<?php $repuesto = new CompraRepuestoEquipoArrendado();?>
														<table style="border:solid 1px silver;padding:10px;">
															<tr>
															  <td><?php echo $form->labelEx($repuesto,"repuesto",array('style'=>'width:80px;'));?></td>
															  <td><?php echo $form->textField($repuesto,"[{0}]repuesto",array('id'=>"repuesto{0}",'class'=>'repuesto'));?></td>
															  <td><div id="errorRepuesto{0}" style="color:red;width:100px;"></div></td>
															  <td><?php echo $form->labelEx($repuesto,"montoNeto",array('style'=>'width:80px;'));?></td>
															  <td><?php echo $form->textField($repuesto,"[{0}]montoNeto",array('id'=>"montoNeto{0}",'class'=>'fixedInt'));?></td>
															  <td><div id="errorMontoNeto{0}" style="color:red;width:100px;"></div></td>
															  <td>
															  	<input type="hidden" class="rowIndex" value="{0}" />
															   	<div class="remove" tipo="repuesto" id="removeRepuesto{0}" validate="true">Eliminar</div>
															  </td>															  
															</tr>
															<tr>
															 <td><?php echo $form->labelEx($repuesto,"tipo_documento",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->dropDownList($repuesto,'[{0}]tipo_documento',CHtml::listData(Tools::listarTiposDocumentos() ,'id', 'nombre'));?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($repuesto,"factura",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]factura",array('id'=>"factura{0}"));?></td>
															 <td></td>
															 <td></td>
															</tr>
                                                                                                                        
                                                                                                                        <tr>
															 <td><?php echo $form->labelEx($repuesto,"rut_proveedor",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]rut_proveedor",array('id'=>"rut_proveedorR{0}",'class'=>'rut_proveedorR','i'=>'{0}'));?></td>
															 <td><div id="errorRutProveedorR{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($repuesto,"nombre_proveedor",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]nombre_proveedor",array('id'=>"nombre_proveedorR{0}",'class'=>'nombre_proveedorR','i'=>'{0}'));?></td>
															 <td><div id="errorNombreProveedorR{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
															
															<tr>
                                                                                                                         <td><?php echo $form->labelEx($repuesto,"cuenta",array('style'=>'width:80px;'));?></td>
                                                                                                                         <td><?php echo $form->dropDownList($repuesto,'[{0}]cuenta',CHtml::listData(CuentaContableRepuesto::model()->findAll(), 'nombre', 'nombre'),array('id'=>'cuentaR{0}'));?></td>
															 <td><div id="errorCuentaR_id{0}" style="color:red;width:100px;"></div></td>
															 <td><?php echo $form->labelEx($repuesto,"faena_id",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->dropDownList($repuesto,'[{0}]faena_id',CHtml::listData(Faena::model()->listar(), 'id', 'nombre'),array('id'=>'faenaR_id{0}'));?></td>
															 <td><div id="errorFaenaR_id{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
															
                                                                                                                        <tr>
															 <td><?php echo $form->labelEx($repuesto,"cantidad",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]cantidad",array('id'=>"cantidad{0}"));?><div id="errorCantidad{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															 <td><?php echo $form->labelEx($repuesto,"unidad",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->dropDownList($repuesto,'[{0}]unidad',CHtml::listData(Unidad::model()->findAll(), 'sigla', 'nombre'),array('id'=>'unidad{0}'));?></td>
															 <td></td>
															 <td></td>
															</tr>
                                                                                                                        
															<tr>
															 <td><?php echo $form->labelEx($repuesto,"numero",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]numero",array('id'=>"numeroRep{0}"));?><div id="errorNumeroRep{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($repuesto,"rut_rinde");?></td>
															 <td><?php echo $form->textField($repuesto,'[{0}]rut_rinde',array('style'=>'width:80px;','class'=>'rut_rindeR','id'=>'rut_rindeR{0}','i'=>'{0}'));?> </td>
															 <td></td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($repuesto,"nombre",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,'[{0}]nombre',array('i'=>'{0}','class'=>'nombreR','id'=>'nombreR{0}'));?> </td>
															 <td></td>
															 <td><?php echo $form->labelEx($repuesto,"fechaRendicion",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]fechaRendicion",array('id'=>"fechaRendicionRep{0}",'class'=>'fecha','readonly'=>'readonly'));?></td>
															 <td></td>
                                                                                                                         <td></td>
															</tr>
															
															<tr>
															 
                                                                                                                         <td><?php echo $form->labelEx($repuesto,"guia",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]guia",array('id'=>"guia{0}"));?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($repuesto,"observaciones",array('style'=>'width:80px;'));?></td>
															 <td><?php echo $form->textField($repuesto,"[{0}]observaciones",array('id'=>"observaciones{0}"));?></td>
															 <td></td>
															 <td></td>
															</tr>
														</table>	
													</td>
												</tr>
											</textarea>
										</td>
									</tr>
								</tfoot>
							</table>
						</div><!--panel-->
					</td>
				</tr>
			</table>
		</div><!--complex-->
	</fieldset>

	<fieldset>
		<legend>Datos de Panne</legend>
		<table>
			<tr>
				<td style='width:150px;'>¿Ingresar Panne?</td><td><?php echo $form->checkbox($model,'panne',array('id'=>'checkPanne'));?></td>
			</tr>
			<tr>
				<td colspan='2'>
					<div id='panne'>
						<table>
							<tr>
								<td>Hora Inicio</td>
								<td><?php echo $form->dropDownList($model,"iniPanne",CHtml::listData(Tools::listarHoras(), 'id', 'nombre'),array('class'=>'iniPanne'));?></td>
								<td>Hora Fin</td>
								<td><?php echo $form->dropDownList($model,"finPanne",CHtml::listData(Tools::listarHoras(), 'id', 'nombre'),array('class'=>'finPanne'));?><span id='errorPanne'></span></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>

	<table cellspacing="0" cellpadding="0">

		<tr>
			<td width="30"><?php echo $form->labelEx($model,'observaciones'); ?>
			</td>
			<td><?php echo $form->textArea($model,'observaciones',array('cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?>
			<?php echo $form->error($model,'observaciones'); ?></td>
		</tr>
                <tr>
			<td width="30"><?php echo $form->labelEx($model,'observaciones_obra'); ?>
			</td>
			<td><?php echo $form->textArea($model,'observaciones_obra',array('cols'=>'90','rows'=>'5','style'=>'overflow:auto;resize:none;')); ?>
			<?php echo $form->error($model,'observaciones_obra'); ?></td>
		</tr>
	</table>


	<div class="row buttons">
	<?php echo CHtml::submitButton('Guardar',array('id'=>'guardar')); ?>
	</div>

	<?php $this->endWidget();
	endif;?>

</div>
<!-- form -->

