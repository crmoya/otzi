<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.calculation.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.format.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/template.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery');


?>
<script language="javascript" type="text/javascript">
	$(function() {

		$('.equipo').change(function(e) {
			$('.unidadfaena').empty();
			$('.faenaT').val("");
			$('.cantidad').val(0);
			$('.totalT').val(0);
			$('.labelPUt').val(0);
		});

		$(document.body).on('change', '.faenaT', function(e) {
			$('#unidadfaena' + i).empty();
			var id = $(this).attr("id");
			var i = id.substring(id.length - 1);
			var faena_id = $(this).val();
			var camion_id = $("#REquipoPropio_equipoPropio_id").val();
			$("#errorFaenaT_id" + i).html("");
			$.ajax({
				type: 'POST',
				cache: false,
				url: '<?= CController::createUrl('//faena/listunits') ?>',
				data: {
					faena_id: faena_id,
					camion_id: camion_id,
					equipo: "equipo"
				},
				success: function(msg) {
					if (msg == "") {
						$("#errorFaenaT_id" + i).html('ERROR: La faena no tiene unidades de tiempo disponibles');
						$('#unidadfaena' + i).empty();
						$("#puT" + i).attr("pu", 0);
						$("#labelPUt" + i).val(0);
					} else {
						var msgArr = msg.split("-||-");
						if (msgArr[0] == "") {
							$("#errorFaenaT_id" + i).html('ERROR: La faena no tiene unidades de tiempo disponibles para este vehículo');
						} else {
							$("#errorFaenaT_id" + i).html("");
						}
						$('#unidadfaena' + i).html(msgArr[0]);
						$("#puT" + i).attr("pu", msgArr[1]);
						$("#labelPUt" + i).val(msgArr[1]);
					}
					$('#cantidad' + i).val(0);
					$('#totalT' + i).val(0);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});

		});

		$(document.body).on('change', '.unidadfaena', function(e) {
			var id = $(this).attr("id");
			var i = id.substring(id.length - 1);
			$("#puT" + i).attr("pu", 0);
			$("#labelPUt" + i).val(0);
			var unidad_id = $(this).val();
			$.ajax({
				type: 'POST',
				cache: false,
				url: '<?= CController::createUrl('//faena/getunit') ?>',
				data: {
					unidad_id: unidad_id,
					equipo: "equipo"
				},
				success: function(msg) {
					if (msg == "ERROR") {
						$("#errorFaenaT_id" + i).html('ERROR: La faena no tiene unidades de tiempo disponibles');
					} else {
						$('#puT' + i).attr('pu', msg);
						$("#labelPUt" + i).val(msg);
					}
					var pu = $('#puT' + i).attr('pu');
					var cantidad = $('#cantidad' + i).val();
					var total = cantidad * pu;
					$('#totalT' + i).val(total.toFixed(2));
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});

		});

		$(document.body).on('change', '.cantidad', function(e) {
			var id = $(this).attr("id");
			var i = id.substring(id.length - 1);
			var pu = $('#puT' + i).attr('pu');
			if (pu == 0) {
				$.ajax({
					type: 'POST',
					cache: false,
					url: '<?= CController::createUrl('//faena/getunit') ?>',
					data: {
						unidad_id: $('#unidadfaena' + i).val()
					},
					success: function(msg) {
						if (msg == "ERROR") {
							$("#errorFaenaT_id" + i).html('ERROR: La faena no tiene unidades de tiempo disponibles');
						} else {
							$('#puT' + i).attr('pu', msg);
							$("#labelPUt" + i).val(msg);
						}
						pu = $('#puT' + i).attr('pu');
						var cantidad = $('#cantidad' + i).val();
						var total = cantidad * pu;
						console.log(pu + " " + cantidad);
						$('#totalT' + i).val(total.toFixed(2));
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {

					}
				});
			} else {
				var cantidad = $('#cantidad' + i).val();
				var total = cantidad * pu;
				$('#totalT' + i).val(total.toFixed(2));
			}

		});

		$('.faenaT').each(function(e) {
			var faenaId = $(this).val();
			var camion_id = $("#REquipoPropio_equipoPropio_id").val();
			var id = $(this).attr('id');
			var selunidad = $(this).attr('selunidad');
			var i = id.substring(id.length - 1);
			$.ajax({
				type: 'POST',
				cache: false,
				url: '<?= CController::createUrl('//faena/listunits') ?>',
				data: {
					faena_id: faenaId,
					camion_id: camion_id,
					selunidad: selunidad,
					equipo: "equipo"
				},
				success: function(msg) {
					$('#unidadfaena' + i).html(msg);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {

				}
			});
		});

		var proveedores = Array();
		proveedores = [<?php
						$resp = "";
						$proveedores = Proveedor::model()->findAll();
						foreach ($proveedores as $proveedor)
							$resp .= "'" . $proveedor->rut . "',";
						if (count($proveedores) > 0) {
							$resp = substr($resp, 0,  strlen($resp) - 1);
						}
						echo $resp;
						?>];
		$(document.body).on('focus', '.rut_proveedor', function(e) {
			var i = $(this).attr('i');
			$(this).autocomplete({
				source: proveedores,
				select: function(ev, ui) {
					var rut = ui.item.value;
					$.ajax({
						type: "POST",
						url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/'); ?>",
						data: {
							rut: rut
						}
					}).done(function(msg) {
						var disponibles = msg.split("|");
						if (disponibles.length > 0) {
							$('#nombre_proveedor' + i).val(disponibles[0]);
						}
					});
				}
			});
			if (proveedores.length > 0) {
				$('#rut_proveedor' + i).data("ui-Autocomplete").search(' ');
			}
		});

		$(document.body).on('focus', '.rut_proveedorR', function(e) {
			var i = $(this).attr('i');
			$(this).autocomplete({
				source: proveedores,
				select: function(ev, ui) {
					var rut = ui.item.value;
					$.ajax({
						type: "POST",
						url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/'); ?>",
						data: {
							rut: rut
						}
					}).done(function(msg) {
						var disponibles = msg.split("|");
						if (disponibles.length > 0) {
							$('#nombre_proveedorR' + i).val(disponibles[0]);
						}
					});
				}
			});
			if (proveedores.length > 0) {
				$('#rut_proveedorR' + i).data("ui-Autocomplete").search(' ');
			}
		});

		$(document.body).on('keyup', '.rut_proveedor', function(e) {
			var rut = $(this).val();
			var i = $(this).attr('i');
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/'); ?>",
				data: {
					rut: rut
				}
			}).done(function(msg) {
				var disponibles = msg.split("|");
				$('#nombre_proveedor' + i).autocomplete({
					source: disponibles,
					select: function(ev, ui) {
						var nombre = ui.item.value;
						$.ajax({
							type: "POST",
							url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRutExacto/'); ?>",
							data: {
								nombre: nombre
							}
						}).done(function(msg) {
							var disponibles = msg.split("|");
							if (disponibles.length > 0) {
								$('#rut_proveedor' + i).val(disponibles[0]);
							}
						});
					}
				});
				if (disponibles.length > 0) {
					$('#nombre_proveedor' + i).data("ui-Autocomplete").search(' ');
				}
			});
		});
		$(document.body).on('keyup', '.nombre_proveedorR', function(e) {
			var nombre = $(this).val();
			var i = $(this).attr('i');
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRut/'); ?>",
				data: {
					nombre: nombre
				}
			}).done(function(msg) {
				var disponibles = msg.split("|");
				$('#rut_proveedorR' + i).autocomplete({
					source: disponibles,
					select: function(ev, ui) {
						var rut = ui.item.value;
						$.ajax({
							type: "POST",
							url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/'); ?>",
							data: {
								rut: rut
							}
						}).done(function(msg) {
							var disponibles = msg.split("|");
							if (disponibles.length > 0) {
								$('#nombre_proveedorR' + i).val(disponibles[0]);
							}
						});
					}
				});
				if (disponibles.length > 0) {
					$('#rut_proveedorR' + i).data("ui-Autocomplete").search(' ');
				}
			});
		});


		$(document.body).on('keyup', '.rut_proveedor', function(e) {
			var rut = $(this).val();
			var i = $(this).attr('i');
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/'); ?>",
				data: {
					rut: rut
				}
			}).done(function(msg) {
				var disponibles = msg.split("|");
				$('#nombre_proveedor' + i).autocomplete({
					source: disponibles,
					select: function(ev, ui) {
						var nombre = ui.item.value;
						$.ajax({
							type: "POST",
							url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRutExacto/'); ?>",
							data: {
								nombre: nombre
							}
						}).done(function(msg) {
							var disponibles = msg.split("|");
							if (disponibles.length > 0) {
								$('#rut_proveedor' + i).val(disponibles[0]);
							}
						});
					}
				});
				if (disponibles.length > 0) {
					$('#nombre_proveedor' + i).data("ui-Autocomplete").search(' ');
				}
			});
		});
		$(document.body).on('keyup', '.nombre_proveedor', function(e) {
			var nombre = $(this).val();
			var i = $(this).attr('i');
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//operativo/proveedorRut/'); ?>",
				data: {
					nombre: nombre
				}
			}).done(function(msg) {
				var disponibles = msg.split("|");
				$('#rut_proveedor' + i).autocomplete({
					source: disponibles,
					select: function(ev, ui) {
						var rut = ui.item.value;
						$.ajax({
							type: "POST",
							url: "<?php echo Yii::app()->createUrl('//operativo/proveedor/'); ?>",
							data: {
								rut: rut
							}
						}).done(function(msg) {
							var disponibles = msg.split("|");
							if (disponibles.length > 0) {
								$('#nombre_proveedor' + i).val(disponibles[0]);
							}
						});
					}
				});
				if (disponibles.length > 0) {
					$('#rut_proveedor' + i).data("ui-Autocomplete").search(' ');
				}
			});
		});


		$('#habilitar').click(function(e) {
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//rEquipoPropio/unlock/'); ?>",
				data: {
					admin1: $('#REquipoPropio_administrador_1').val(),
					admin2: $('#REquipoPropio_administrador_2').val(),
					aut1: $('#REquipoPropio_clave_admin_1').val(),
					aut2: $('#REquipoPropio_clave_admin_2').val(),
					report_id: <?php echo $model->id; ?>
				}
			}).done(function(msg) {
				if (msg != 'OK') {
					alert(msg);
				} else {
					$('#mydialog').dialog('close');
					window.location = "<?php echo CController::createUrl('//rEquipoPropio/update/' . $model->id); ?>";
					alert('Report correctamente habilitado para modificación.');
				}
			});
		});

		$(document.body).on('keyup', '.rut_rinde', function(e) {
			var rut = $(this).val();
			var i = $(this).attr('i');
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//operativo/rendidor/'); ?>",
				data: {
					rut: rut
				}
			}).done(function(msg) {
				var disponibles = msg.split("|");
				$('#nombre' + i).autocomplete({
					source: disponibles,
					select: function(ev, ui) {
						var nombre = ui.item.value;
						$.ajax({
							type: "POST",
							url: "<?php echo Yii::app()->createUrl('//operativo/rendidorRutExacto/'); ?>",
							data: {
								nombre: nombre
							}
						}).done(function(msg) {
							var disponibles = msg.split("|");
							if (disponibles.length > 0) {
								$('#rut_rinde' + i).val(disponibles[0]);
							}
						});
					}
				});
				if (disponibles.length > 0) {
					$('#nombre' + i).data("ui-Autocomplete").search(' ');
				}
			});
		});
		$(document.body).on('keyup', '.nombre', function(e) {
			var nombre = $(this).val();
			var i = $(this).attr('i');
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//operativo/rendidorRut/'); ?>",
				data: {
					nombre: nombre
				}
			}).done(function(msg) {
				var disponibles = msg.split("|");
				$('#rut_rinde' + i).autocomplete({
					source: disponibles,
					select: function(ev, ui) {
						var rut = ui.item.value;
						$.ajax({
							type: "POST",
							url: "<?php echo Yii::app()->createUrl('//operativo/rendidor/'); ?>",
							data: {
								rut: rut
							}
						}).done(function(msg) {
							var disponibles = msg.split("|");
							if (disponibles.length > 0) {
								$('#nombre' + i).val(disponibles[0]);
							}
						});
					}
				});
				if (disponibles.length > 0) {
					$('#rut_rinde' + i).data("ui-Autocomplete").search(' ');
				}
			});
		});

		$(document.body).on('keyup', '.rut_rindeR', function(e) {
			var rut = $(this).val();
			var i = $(this).attr('i');
			$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('//operativo/rendidor/'); ?>",
				data: {
					rut: rut
				}
			}).done(function(msg) {
				var disponibles = msg.split("|");
				$('#nombreR' + i).autocomplete({
					source: disponibles,
					select: function(ev, ui) {
						var nombre = ui.item.value;
						$.ajax({
							type: "POST",
							url: "<?php echo Yii::app()->createUrl('//operativo/rendidorRutExacto/'); ?>",
							data: {
								nombre: nombre
							}
						}).done(function(msg) {
							var disponibles = msg.split("|");
							if (disponibles.length > 0) {
								$('#rut_rindeR' + i).val(disponibles[0]);
							}
						});
					}
				});
				if (disponibles.length > 0) {
					$('#nombreR' + i).data("ui-Autocomplete").search(' ');
				}
			});
		});




		$("#equipo").html('<?php echo $codigo; ?>');

		<?php
		foreach ($cargas as $carga) {
			echo "nCombustibles++;";
		}
		foreach ($compras as $compra) {
			echo "nRepuestos++;";
		}
		?>

		function checkUnidades() {
			var ok = true;
			$('.unidadfaena').each(function(e) {
				var valor = $(this).val();
				var id = $(this).attr('id');
				var i = id.substring(id.length - 1);
				if (valor == null || valor == "") {
					$(this).css('background', 'pink');
					ok = false;
				} else {
					$(this).css('background', 'white');
				}
			});
			return ok;
		}


		$("#guardar").click(function() {
			var valid = true;
			valid = valid && checkNVueltas();
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

			valid = valid && checkRepuesto();
			valid = valid && checkCantidad();
			valid = valid && checkMontoNeto();
			valid = valid && checkHorasPropio();
			valid = valid && checkFaenaRep();
			valid = valid && checkNombreProveedor();
			valid = valid && checkRutProveedor();

			valid = valid && checkPanne();
			valid = valid && checkNumero();

			valid = valid && checkUnidades();


			return valid;
		});

	});
</script>

<?php $this->pageTitle = Yii::app()->name; ?>

<?php if (Yii::app()->user->hasFlash('equiposMessage')) : ?>

	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('equiposMessage'); ?>
	</div>
<?php endif; ?>

<?php if (Yii::app()->user->hasFlash('equiposError')) : ?>

	<div class="flash-error">
		<?php echo Yii::app()->user->getFlash('equiposError'); ?>
	</div>
<?php endif; ?>

<?php if (!Yii::app()->user->hasFlash('equiposMessage') && !Yii::app()->user->hasFlash('equiposError')) : ?>

	<div class="form" style="width:900px;">
		<?php $form = $this->beginWidget('CActiveForm', array(
			'id' => 'equiposPropios-form',
			'enableClientValidation' => true,
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
			'clientOptions' => array(
				'validateOnSubmit' => true,
			),
		)); ?>
		<?php
		$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id' => 'mydialog',
			'options' => array(
				'title' => 'Habilitar Report ' . $model->reporte . ' para modificación',
				'autoOpen' => false,
				'width' => '640px',
			),
		));
		?>
		<div class="form">
			<p class="note">
				Seleccione los usuarios administradores que autorizan la habilitación para modificar el report.
			</p>
			<div class="row">
				<table>
					<tr>
						<td>
							<div>
								<?php echo $form->labelEx($model, 'administrador_1'); ?>
								<?php echo $form->dropDownList($model, 'administrador_1', CHtml::listData(Usuario::model()->findAllAdmin(), 'id', 'user')); ?>
							</div>
						</td>
						<td>
							<div>
								<?php echo $form->labelEx($model, 'clave_admin_1'); ?>
								<?php echo $form->passwordField($model, 'clave_admin_1'); ?>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="row">
				<table>
					<tr>
						<td>
							<div>
								<?php echo $form->labelEx($model, 'administrador_2'); ?>
								<?php echo $form->dropDownList($model, 'administrador_2', CHtml::listData(Usuario::model()->findAllAdmin(), 'id', 'user')); ?>
							</div>
						</td>
						<td>
							<div>
								<?php echo $form->labelEx($model, 'clave_admin_2'); ?>
								<?php echo $form->passwordField($model, 'clave_admin_2'); ?>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="row buttons">
				<?php echo CHtml::submitButton('Habilitar para modificación', array('id' => 'habilitar')); ?>
			</div>
		</div>
		<?php
		$this->endWidget('zii.widgets.jui.CJuiDialog');

		?>

		<?php if ($model->validado == 1 || $model->validado == 2) : ?>
			<div style="color:red">
				ATENCIÓN: ESTE REPORT ESTÁ VALIDADO, PARA MODIFICARLO SE NECESITA LA AUTORIZACIÓN DE 2 ADMINISTRADORES<br />
				Si desea modificar el report
				<?php echo CHtml::link('HAGA CLICK ACÁ', '#', array(
					'onclick' => '$("#mydialog").dialog("open"); return false;',
				)); ?>

			</div><br />
		<?php endif; ?>
		<p class="note" id="note">
			Campos con <span class="required">*</span> son requeridos.
		</p>

		<fieldset>
			<legend>Reporte de Equipo Propio</legend>
			<table>
				<tr>
					<td>
						<?php echo $form->labelEx($model, 'fecha'); ?>
					</td>
					<td>
						<?php
						$this->widget(
							'zii.widgets.jui.CJuiDatePicker',
							array(
								'model' => $model,
								'language' => 'es',
								'attribute' => 'fecha',
								// additional javascript options for the date picker plugin
								'options' => array(
									'showAnim' => 'fold',
									'dateFormat' => 'dd/mm/yy',
									'changeYear' => true,
									'changeMonth' => true,
								),
								'htmlOptions' => array(
									'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',
									'style' => 'width:90px;',
									'value' => Tools::backFecha($model->fecha),
								),
							)
						);
						?>
						<?php echo $form->error($model, 'fecha'); ?>
					</td>
					<td width="100"><?php echo $form->labelEx($model, 'equipoPropio_id'); ?>
					</td>
					<td><?php
						echo $form->dropDownList(
							$model,
							'equipoPropio_id',
							CHtml::listData(EquipoPropio::model()->listar(), 'id', 'nombre'),
							array(
								'class' => 'equipo',
								'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',
								'ajax' => array(
									'type' => 'POST', //request type
									'url' => CController::createUrl('//operativo/llenaEquipo'),
									'update' => '#equipo',
								)
							)
						);
						?> <?php echo $form->error($model, 'equipoPropio_id'); ?>
					</td>
				</tr>
				<tr>
					<td style='font-size: 0.9em;'><b>Código:</b></td>
					<td id="equipo"></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>Datos de la Expedición</legend>
			<table>
				<tr>
					<td width="30"><?php echo $form->labelEx($model, 'reporte'); ?></td>
					<td><?php echo $form->textField($model, 'reporte', array('disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?><?php echo $form->error($model, 'reporte'); ?></td>
					<td width="30"><?php echo $form->labelEx($model, 'operador_id'); ?></td>
					<td>
						<?php echo $form->dropDownList($model, 'operador_id', CHtml::listData(Operador::model()->listar(), 'id', 'nombre'), array('disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?>
						<?php echo $form->error($model, 'operador_id'); ?>
					</td>
				</tr>
				<tr>
					<td width="30"><?php echo $form->labelEx($model, 'hInicial'); ?></td>
					<td><?php echo $form->textField($model, 'hInicial', array('id' => "hInicial", 'class' => 'fixedHInicial', 'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?><?php echo $form->error($model, 'hInicial'); ?></td>
					<td width="30"><?php echo $form->labelEx($model, 'hFinal'); ?></td>
					<td><?php echo $form->textField($model, 'hFinal', array('id' => "hFinal", 'class' => 'fixedHFinal', 'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?><?php echo $form->error($model, 'hFinal'); ?></td>
					<td width="30"><?php echo $form->labelEx($model, 'horas'); ?></td>
					<td><?php echo $form->textField($model, 'horas', array('class' => 'fixed', 'style' => 'border:none;background:white;', 'readonly' => 'readonly')); ?><?php echo $form->error($model, 'horas'); ?></td>
				</tr>
				<tr>
					<td width="30"><?php echo $form->labelEx($model, 'horasGps'); ?></td>
					<td><?php echo $form->textField($model, 'horasGps', array('class' => 'fixed', 'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?><?php echo $form->error($model, 'horas'); ?></td>
					
				</tr>
			</table>
		</fieldset>


		<fieldset>
			<legend>Expediciones con PU por tiempo</legend>
			<div class="complex">
				<table>
					<tr>
						<td style="vertical-align:top;">
							<div>
								<table class="templateFrame grid" cellspacing="0">
									<tbody class="templateTarget">
										<?php for ($i = 0; $i < count($viajesT); $i++) :
											$expedicion = $viajesT[$i]; ?>
											<tr class="templateContent">
												<td width="100px">
													<table style="border:solid 1px silver;padding:10px;">
														<tr>
															<td><?php echo $form->labelEx($expedicion, "faena_id", array('style' => 'width:80px;',)); ?></td>
															<td><?php echo $form->dropDownList($expedicion, "[$i]faena_id", CHtml::listData(Faena::model()->listarPorTiempoE($expedicion->faena_id), 'id', 'nombre'), array('id' => "faenaT_id$i", 'class' => 'faenaT', 'selUnidad' => isset($expedicion->unidadfaenaEquipo) ? $expedicion->unidadfaenaEquipo->id : "", 'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorFaenaT_id<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td><?php echo $form->labelEx($expedicion, "unidadfaena_equipo_id", array('style' => 'width:80px;')); ?></td>
															<td><select name="Expedicionportiempoeq[<?php echo $i; ?>][unidadfaena_equipo_id]" <?php echo $model->validado == 1 || $model->validado == 2 ? 'disabled' : ''; ?> class="unidadfaena" id="unidadfaena<?php echo $i; ?>">
																	<option>Seleccione unidad de tiempo</option>
																</select></td>
															<td id="puT<?php echo $i; ?>" pu=""></td>
															<td>
																<input type="hidden" class="rowIndex" value="<?php echo $i; ?>" />
																<?php if ($model->validado == 0) : ?>
																	<div class="remove" tipo="expedicionT" id="removeExpedicion<?php echo $i; ?>" validate="true">Eliminar</div>
																<?php else : ?>
																	<div tipo="expedicion" id="removeExpedicion<?php echo $i; ?>" validate="true"></div>
																<?php endif; ?>
															</td>
														</tr>
														<tr>

															<td><?php echo $form->labelEx($expedicion, "cantidad", array('style' => 'width:80px;',)); ?></td>
															<td><?php echo $form->textField($expedicion, "[$i]cantidad", array('id' => "cantidad$i", 'class' => 'fixed cantidad', 'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorCantidad<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td><label><b>PU</b></label></td>
															<td><input class="labelPUt" id="labelPUt<?= $i ?>" type="text" value="<?= isset($expedicion->unidadfaenaEquipo) ? $expedicion->unidadfaenaEquipo->pu : "" ?>" readonly="readonly" enabled="disabled" /></td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<td><?php echo $form->labelEx($expedicion, "total", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($expedicion, "[$i]total", array('id' => "totalT$i", 'class' => 'fixed totalT', 'readonly' => 'readonly', 'disabled' => $model->validado == 1 || $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorTotal<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td></td>
														</tr>

													</table>
												</td>
											</tr>
										<?php endfor; ?>
									</tbody>
									<tfoot>
										<tr>
											<td>
												<?php if ($model->validado == 0) : ?>
													<div class="add" tipo="expedicionT">Agregar</div>
												<?php endif; ?>
												<textarea class="template" rows="0" cols="0">
											<tr class="templateContent">
												<td width="100px">
													<?php $expedicion = new Expedicionportiempoeq(); ?>
													<table style="border:solid 1px silver;padding:10px;">
														<tr>
															<td><?php echo $form->labelEx($expedicion, "faena_id", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($expedicion, '[{0}]faena_id', CHtml::listData(Faena::model()->listarPorTiempoE(), 'id', 'nombre'), array('id' => 'faena_idT{0}', 'class' => 'faenaT')); ?></td>
															<td><div id="errorFaenaT_id{0}" style="color:red;width:100px;"></div></td>
															<td><?php echo $form->labelEx($expedicion, "unidadfaena_equipo_id", array('style' => 'width:80px;')); ?></td>	
															<td><select name="Expedicionportiempoeq[{0}][unidadfaena_equipo_id]" class="unidadfaena" id="unidadfaena{0}"></select></td>	
															<td id="puT{0}" pu=""></td>	
															<td>
															<input type="hidden" class="rowIndex" value="{0}" />
																															<?php if ($model->validado == 0) : ?>
															<div class="remove" tipo="expedicionT" id="removeExpedicion{0}" validate="true">Eliminar</div>
																															<?php endif; ?>
															</td>															  
														</tr>
														<tr>
															<td><?php echo $form->labelEx($expedicion, "cantidad", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($expedicion, "[{0}]cantidad", array('id' => "cantidad{0}", 'class' => 'cantidad fixed')); ?></td>
															<td><div id="errorCantidad{0}" style="color:red;width:100px;"></div></td>
															<td><label><b>PU</b></label></td>
															<td><input class="labelPUt" id="labelPUt{0}" type="text" value="0.00" readonly="readonly" enabled="disabled"/></td>
															<td></td>
														</tr>
														<tr>
															<td><?php echo $form->labelEx($expedicion, "total", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($expedicion, "[{0}]total", array('id' => "totalT{0}", 'class' => 'fixed totalT', 'readonly' => 'readonly')); ?></td>
															<td><div id="errorTotalT{0}" style="color:red;width:100px;"></div></td>
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
							</div>
							<!--panel-->
						</td>
					</tr>
				</table>
			</div>
			<!--complex-->
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
										<?php foreach ($cargas as $i => $combustible) : ?>
											<tr class="templateContent">
												<td width="100px">
													<table style="border:solid 1px silver;padding:10px;">
														<tr>
															<td><?php echo $form->labelEx($combustible, "faena_id", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($combustible, "[$i]faena_id", CHtml::listData(Faena::model()->listar($combustible->faena_id), 'id', 'nombre'), array('id' => "faenaC_id$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorFaenaC_id<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>
															<td></td>
															<td></td>
															<td>
																<input type="hidden" class="rowIndex" value="<?php echo $i; ?>" />
																<?php if ($model->validado != 2) : ?>
																	<div class="remove" tipo="combustible" id="removeCombustible<?php echo $i; ?>" validate="true">Eliminar</div>
																<?php else : ?>
																	<div tipo="combustible" id="removeCombustible<?php echo $i; ?>" validate="true"></div>
																<?php endif; ?>
															</td>
														</tr>
														<tr>
															<td><?php echo $form->labelEx($combustible, "tipo_documento", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($combustible, '[' . $i . ']tipo_documento', CHtml::listData(Tools::listarTiposDocumentosComb(), 'id', 'nombre'), array('disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>
															<td><?php echo $form->labelEx($combustible, "factura", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]factura", array('id' => "factura$i", 'class' => "factura", 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorFactura{0}" style="color:red;width:100px;"></div>
															</td>

															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($combustible, "rut_proveedor", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[" . $i . "]rut_proveedor", array('id' => "rut_proveedor" . $i, 'class' => 'rut_proveedor', 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td>
																<div id="errorRutProveedor<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td><?php echo $form->labelEx($combustible, "nombre_proveedor", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[" . $i . "]nombre_proveedor", array('id' => "nombre_proveedor" . $i, 'class' => 'nombre_proveedor', 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td>
																<div id="errorNombreProveedor<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($combustible, "petroleoLts", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]petroleoLts", array('id' => "petroleoLts$i", 'class' => 'fixedPetroleoLts', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorPetroleoLts<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td><?php echo $form->labelEx($combustible, "hCarguio", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]hCarguio", array('id' => "kmCarguio$i", 'class' => 'fixed', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorKmCarguio<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($combustible, "precioUnitario", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]precioUnitario", array('id' => "precioUnitario$i", 'class' => 'fixedPrecio', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorPrecioUnitario<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td><?php echo $form->labelEx($combustible, "valorTotal", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]valorTotal", array('id' => "valorTotal$i", 'class' => 'fixedValor', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorValorTotal<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($combustible, "tipoCombustible_id", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($combustible, "[$i]tipoCombustible_id", CHtml::listData(TipoCombustible::model()->listar(), 'id', 'nombre'), array('id' => "tipoCombustible$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorTipoCombustible<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td><?php echo $form->labelEx($combustible, "supervisorCombustible_id", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($combustible, "[$i]supervisorCombustible_id", CHtml::listData(SupervisorCombustible::model()->listar(), 'id', 'nombre'), array('id' => "supervisor$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorSupervisor<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td>
															</td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($combustible, "numero", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]numero", array('i' => $i, 'class' => 'nroRendicion', 'id' => "numero$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?><div id="errorNumero<?php echo $i; ?>" class="errorMessage errorNumero"></div>
															</td>
															<td></td>

															<td><?php echo $form->labelEx($combustible, "rut_rinde", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]rut_rinde", array('class' => 'rut_rinde', 'id' => "rut_rinde$i", 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($combustible, "fechaRendicion", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]fechaRendicion", array('id' => "fechaRendicion$i", 'class' => 'fecha', 'readonly' => 'readonly', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>

															<td><?php echo $form->labelEx($combustible, "nombre", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]nombre", array('id' => "nombre$i", 'class' => 'nombre_rinde', 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($combustible, "observaciones", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[" . $i . "]observaciones", array('id' => "observaciones$i", 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?><div id="errorObservaciones<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>

															<td><?php echo $form->labelEx($combustible, "guia", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($combustible, "[$i]guia", array('id' => "guia$i", 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?><div id="errorGuia<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>
															<td></td>
														</tr>

													</table>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td>
												<?php if ($model->validado != 2) : ?>
													<div class="add" tipo="combustible">Agregar</div>
												<?php endif; ?>
												<textarea class="template" rows="0" cols="0">
												<tr class="templateContent">
													<td width="100px">
														<?php $combustible = new CargaCombEquipoPropio(); ?>
														<table style="border:solid 1px silver;padding:10px;">
															<tr>
															  <td><?php echo $form->labelEx($combustible, "faena_id", array('style' => 'width:80px;')); ?></td>
															  <td><?php echo $form->dropDownList($combustible, '[{0}]faena_id', CHtml::listData(Faena::model()->listar(), 'id', 'nombre'), array('id' => 'faenaC_id{0}')); ?></td>
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
															 <td><?php echo $form->labelEx($combustible, "tipo_documento", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->dropDownList($combustible, '[{0}]tipo_documento', CHtml::listData(Tools::listarTiposDocumentosComb(), 'id', 'nombre')); ?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible, "factura", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]factura", array('id' => "factura{0}", 'class' => "factura", 'i' => '{0}')); ?></td>
															 <td><div id="errorFactura{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
                                                                                                                        
                                                                                                                        <tr>
															 <td><?php echo $form->labelEx($combustible, "rut_proveedor", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]rut_proveedor", array('id' => "rut_proveedor{0}", 'class' => 'rut_proveedor', 'i' => '{0}')); ?></td>
															 <td><div id="errorRutProveedor{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($combustible, "nombre_proveedor", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]nombre_proveedor", array('id' => "nombre_proveedor{0}", 'class' => 'nombre_proveedor', 'i' => '{0}')); ?></td>
															 <td><div id="errorNombreProveedor{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($combustible, "petroleoLts", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]petroleoLts", array('id' => "petroleoLts{0}", 'class' => 'fixedPetroleoLts')); ?></td>
															 <td><div id="errorPetroleoLts{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($combustible, "hCarguio", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]hCarguio", array('id' => "kmCarguio{0}", 'class' => 'fixed')); ?></td>
															 <td><div id="errorKmCarguio{0}" style="color:red;width:100px;"></div></td>
															 
															 <td></td>
															</tr>
                                                                                                                        															
															<tr>
															 <td><?php echo $form->labelEx($combustible, "precioUnitario", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]precioUnitario", array('id' => "precioUnitario{0}", 'class' => 'fixedPrecio')); ?></td>
															 <td><div id="errorPrecioUnitario{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($combustible, "valorTotal", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]valorTotal", array('id' => "valorTotal{0}", 'class' => 'fixedValor')); ?></td>
															 <td><div id="errorValorTotal{0}" style="color:red;width:100px;"></div></td>
															 
															 <td></td>
															</tr>
															
															<tr>
															  <td><?php echo $form->labelEx($combustible, "tipoCombustible_id", array('style' => 'width:80px;')); ?></td>
                                                                                                                          <td><?php echo $form->dropDownList($combustible, '[{0}]tipoCombustible_id', CHtml::listData(TipoCombustible::model()->findAll(), 'id', 'nombre'), array('id' => 'tipoCombustible{0}')); ?></td>
															  <td><div id="errorTipoCombustible{0}" style="color:red;width:100px;"></div></td>
															  
															  <td><?php echo $form->labelEx($combustible, "supervisorCombustible_id", array('style' => 'width:80px;')); ?></td>
															  <td><?php echo $form->dropDownList($combustible, '[{0}]supervisorCombustible_id', CHtml::listData(SupervisorCombustible::model()->listar(), 'id', 'nombre'), array('id' => 'supervisor{0}')); ?></td>
															  <td><div id="errorSupervisor{0}" style="color:red;width:100px;"></div></td>	
															  <td>
															  </td>															  
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($combustible, "numero", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]numero", array('i' => '{0}', 'class' => 'nroRendicion', 'id' => "numero{0}")); ?><div id="errorNumero{0}" class="errorMessage errorNumero"></div></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible, "rut_rinde"); ?></td>
															 <td><?php echo $form->textField($combustible, '[{0}]rut_rinde', array('style' => 'width:80px;', 'class' => 'rut_rinde', 'id' => 'rut_rinde{0}', 'i' => '{0}')); ?> </td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($combustible, "fechaRendicion", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]fechaRendicion", array('id' => "fechaRendicion{0}", 'class' => 'fecha', 'readonly' => 'readonly')); ?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible, "nombre", array('style' => 'width:80px;')); ?></td>
                                                                                                                         <td><?php echo $form->textField($combustible, '[{0}]nombre', array('i' => '{0}', 'class' => 'nombre', 'id' => 'nombre{0}')); ?> </td>
															 <td></td>
															 <td></td>
															</tr>
                                                                                                                        
                                                                                                                        <tr>
															 <td><?php echo $form->labelEx($combustible, "observaciones", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]observaciones", array('id' => "observaciones{0}")); ?><div id="errorObservaciones{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($combustible, "guia", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($combustible, "[{0}]guia", array('id' => "guia{0}")); ?><div id="errorGuia{0}" style="color:red;width:100px;"></div></td>
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
							</div>
							<!--panel-->
						</td>
					</tr>
				</table>
			</div>
			<!--complex-->
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
										<?php foreach ($compras as $i => $repuesto) : ?>
											<tr class="templateContent">
												<td width="100px">
													<table style="border:solid 1px silver;padding:10px;">
														<tr>
															<td><?php echo $form->labelEx($repuesto, "repuesto", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]repuesto", array('id' => "repuesto$i", 'class' => 'repuesto', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorRepuesto<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td><?php echo $form->labelEx($repuesto, "montoNeto", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]montoNeto", array('id' => "montoNeto$i", 'class' => 'fixedInt', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td>
																<div id="errorMontoNeto<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td>
																<input type="hidden" class="rowIndex" value="<?php echo $i; ?>" />
																<?php if ($model->validado != 2) : ?>
																	<div class="remove" tipo="repuesto" id="removeRepuesto<?php echo $i; ?>" validate="true">Eliminar</div>
																<?php else : ?>
																	<div tipo="repuesto" id="removeRepuesto<?php echo $i; ?>" validate="true"></div>
																<?php endif; ?>
															</td>
														</tr>
														<tr>
															<td><?php echo $form->labelEx($repuesto, "tipo_documento", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($repuesto, '[' . $i . ']tipo_documento', CHtml::listData(Tools::listarTiposDocumentos(), 'id', 'nombre'), array('id' => "tipoDocumento$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>

															<td><?php echo $form->labelEx($repuesto, "factura", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]factura", array('id' => "factura$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($repuesto, "rut_proveedor", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]rut_proveedor", array('id' => "rut_proveedorR$i", 'class' => 'rut_proveedorR', 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td>
																<div id="errorRutProveedorR<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>

															<td><?php echo $form->labelEx($repuesto, "nombre_proveedor", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]nombre_proveedor", array('id' => "nombre_proveedorR$i", 'class' => 'nombre_proveedorR', 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td>
																<div id="errorNombreProveedorR<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($repuesto, "cuenta", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($repuesto, '[' . $i . ']cuenta', CHtml::listData(CuentaContableRepuesto::model()->findAll(), 'nombre', 'nombre'), array('id' => 'cuentaR' . $i, 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td>
																<div id="errorCuentaR_id<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td><?php echo $form->labelEx($repuesto, "faena_id", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($repuesto, '[' . $i . ']faena_id', CHtml::listData(Faena::model()->listar($repuesto->faena_id), 'id', 'nombre'), array('id' => 'faenaR_id' . $i, 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td>
																<div id="errorFaenaR_id<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($repuesto, "cantidad", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]cantidad", array('id' => "cantidadR$i", 'disabled' => $model->validado == 2 ? 'disabled' : '','class'=>'fixed')); ?><div id="errorCantidadR<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>
															<td><?php echo $form->labelEx($repuesto, "unidad", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->dropDownList($repuesto, "[$i]unidad", CHtml::listData(Unidad::model()->findAll(), 'sigla', 'nombre'), array('id' => "unidad$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($repuesto, "numero", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]numero", array('id' => "numeroRep$i", 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?><div id="errorNumeroRep<?php echo $i; ?>" style="color:red;width:100px;"></div>
															</td>
															<td></td>

															<td><?php echo $form->labelEx($repuesto, "rut_rinde"); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]rut_rinde", array('style' => 'width:80px;', 'class' => 'rut_rindeR', 'id' => "rut_rindeR$i", 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?> </td>
															<td></td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $form->labelEx($repuesto, "nombre", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]nombre", array('class' => 'nombre_rindeR', 'id' => "nombreR$i", 'i' => $i, 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?> </td>
															<td></td>
															<td><?php echo $form->labelEx($repuesto, "fechaRendicion", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]fechaRendicion", array('id' => "fechaRendicionRep$i", 'class' => 'fecha', 'readonly' => 'readonly', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?></td>
															<td></td>


														</tr>
														<tr>

															<td><?php echo $form->labelEx($repuesto, "guia", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]guia", array('id' => "guia$i", 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td></td>

															<td><?php echo $form->labelEx($repuesto, "observaciones", array('style' => 'width:80px;')); ?></td>
															<td><?php echo $form->textField($repuesto, "[$i]observaciones", array('id' => "observaciones$i", 'disabled' => $model->validado == 2 ? 'disabled' : '')); ?></td>
															<td></td>
															<td></td>
														</tr>

													</table>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td>
												<?php if ($model->validado != 2) : ?>
													<div class="add" tipo="repuesto">Agregar</div>
												<?php endif; ?>
												<textarea class="template" rows="0" cols="0">
												<tr class="templateContent">
													<td width="100px">
														<?php $repuesto = new CompraRepuestoEquipoPropio(); ?>
														<table style="border:solid 1px silver;padding:10px;">
															<tr>
															  <td><?php echo $form->labelEx($repuesto, "repuesto", array('style' => 'width:80px;')); ?></td>
															  <td><?php echo $form->textField($repuesto, "[{0}]repuesto", array('id' => "repuesto{0}", 'class' => 'repuesto')); ?></td>
															  <td><div id="errorRepuesto{0}" style="color:red;width:100px;"></div></td>
															  <td><?php echo $form->labelEx($repuesto, "montoNeto", array('style' => 'width:80px;')); ?></td>
															  <td><?php echo $form->textField($repuesto, "[{0}]montoNeto", array('id' => "montoNeto{0}", 'class' => 'fixedInt')); ?></td>
															  <td><div id="errorMontoNeto{0}" style="color:red;width:100px;"></div></td>
															  <td>
															  	<input type="hidden" class="rowIndex" value="{0}" />
															   	<div class="remove" tipo="repuesto" id="removeRepuesto{0}" validate="true">Eliminar</div>
															  </td>															  
															</tr>
															<tr>
															 <td><?php echo $form->labelEx($repuesto, "tipo_documento", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->dropDownList($repuesto, '[{0}]tipo_documento', CHtml::listData(Tools::listarTiposDocumentos(), 'id', 'nombre')); ?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($repuesto, "factura", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]factura", array('id' => "factura{0}")); ?></td>
															 <td></td>
															 <td></td>
															</tr>
                                                                                                                        
                                                                                                                        <tr>
															 <td><?php echo $form->labelEx($repuesto, "rut_proveedor", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]rut_proveedor", array('id' => "rut_proveedorR{0}", 'class' => 'rut_proveedorR', 'i' => '{0}')); ?></td>
															 <td><div id="errorRutProveedorR{0}" style="color:red;width:100px;"></div></td>
															 
															 <td><?php echo $form->labelEx($repuesto, "nombre_proveedor", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]nombre_proveedor", array('id' => "nombre_proveedorR{0}", 'class' => 'nombre_proveedorR', 'i' => '{0}')); ?></td>
															 <td><div id="errorNombreProveedorR{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($repuesto, "cuenta", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->dropDownList($repuesto, '[{0}]cuenta', CHtml::listData(CuentaContableRepuesto::model()->findAll(), 'nombre', 'nombre'), array('id' => 'cuentaR{0}')); ?></td>
															 <td><div id="errorCuentaR_id{0}" style="color:red;width:100px;"></div></td>
															 <td><?php echo $form->labelEx($repuesto, "faena_id", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->dropDownList($repuesto, '[{0}]faena_id', CHtml::listData(Faena::model()->listar(), 'id', 'nombre'), array('id' => 'faenaR_id{0}')); ?></td>
															 <td><div id="errorFaenaR_id{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($repuesto, "cantidad", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]cantidad", array('id' => "cantidadR{0}",'class'=>'fixed')); ?><div id="errorCantidadR{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															 <td><?php echo $form->labelEx($repuesto, "unidad", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->dropDownList($repuesto, '[{0}]unidad', CHtml::listData(Unidad::model()->findAll(), 'sigla', 'nombre'), array('id' => 'unidad{0}')); ?></td>
															 <td></td>
															 <td></td>
															</tr>
                                                                                                                        
															<tr>
															 <td><?php echo $form->labelEx($repuesto, "numero", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]numero", array('id' => "numeroRep{0}")); ?><div id="errorNumeroRep{0}" style="color:red;width:100px;"></div></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($repuesto, "rut_rinde"); ?></td>
															 <td><?php echo $form->textField($repuesto, '[{0}]rut_rinde', array('style' => 'width:80px;', 'class' => 'rut_rindeR', 'id' => 'rut_rindeR{0}', 'i' => '{0}')); ?> </td>
															 <td></td>
															 <td></td>
															</tr>
															
															<tr>
															 <td><?php echo $form->labelEx($repuesto, "nombre", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, '[{0}]nombre', array('i' => '{0}', 'class' => 'nombreR', 'id' => 'nombreR{0}')); ?> </td>
															 <td></td>
															 <td><?php echo $form->labelEx($repuesto, "fechaRendicion", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]fechaRendicion", array('id' => "fechaRendicionRep{0}", 'class' => 'fecha', 'readonly' => 'readonly')); ?></td>
															 <td></td>
                                                                                                                         <td></td>
															</tr>
															
															<tr>
															 
                                                                                                                         <td><?php echo $form->labelEx($repuesto, "guia", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]guia", array('id' => "guia{0}")); ?></td>
															 <td></td>
															 
															 <td><?php echo $form->labelEx($repuesto, "observaciones", array('style' => 'width:80px;')); ?></td>
															 <td><?php echo $form->textField($repuesto, "[{0}]observaciones", array('id' => "observaciones{0}")); ?></td>
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
							</div>
							<!--panel-->
						</td>
					</tr>
				</table>
			</div>
			<!--complex-->
		</fieldset>

		<fieldset>
			<legend>Datos de Panne</legend>
			<table>
				<tr>
					<td style='width:150px;'>¿Ingresar Panne?</td>
					<td><?php echo $form->checkbox($model, 'panne', array('id' => 'checkPanne', 'disabled' => $model->validado == 1 ? 'disabled' : '',)); ?></td>
				</tr>
				<tr>
					<td colspan='2'>
						<div id='panne'>
							<table>
								<tr>
									<td>Hora Inicio</td>
									<td><?php echo $form->dropDownList($model, "iniPanne", CHtml::listData(Tools::listarHoras(), 'id', 'nombre'), array('class' => 'iniPanne', 'disabled' => $model->validado == 1 ? 'disabled' : '',)); ?></td>
									<td>Hora Fin</td>
									<td><?php echo $form->dropDownList($model, "finPanne", CHtml::listData(Tools::listarHoras(), 'id', 'nombre'), array('class' => 'finPanne', 'disabled' => $model->validado == 1 ? 'disabled' : '',)); ?><span id='errorPanne'></span></td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</fieldset>

		<table cellspacing="0" cellpadding="0">

			<tr>
				<td width="30"><?php echo $form->labelEx($model, 'observaciones'); ?>
				</td>
				<td><?php echo $form->textArea($model, 'observaciones', array('cols' => '90', 'rows' => '5', 'style' => 'overflow:auto;resize:none;', 'disabled' => $model->validado == 1 ? 'disabled' : '',)); ?>
					<?php echo $form->error($model, 'observaciones'); ?></td>
			</tr>
			<tr>
				<td width="30"><?php echo $form->labelEx($model, 'observaciones_obra'); ?>
				</td>
				<td><?php echo $form->textArea($model, 'observaciones_obra', array('cols' => '90', 'rows' => '5', 'style' => 'overflow:auto;resize:none;', 'disabled' => $model->validado == 1 ? 'disabled' : '',)); ?>
					<?php echo $form->error($model, 'observaciones_obra'); ?></td>
			</tr>
		</table>


		<fieldset>
			<legend>Imágenes y documentos del Report</legend>
			<?php if ($model->validado == 0) : ?>
				<div class="row" style="text-align:right;">
					<a class="btn seleccionar"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/basura.png" /></a>
				</div>
			<?php endif; ?>
			<div class="table table-hover">
				<?php
				$path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'archivos';
				if (!is_dir($path)) {
					mkdir($path);
				}
				$path = $path . DIRECTORY_SEPARATOR . "equipos_propios";
				if (!is_dir($path)) {
					mkdir($path);
				}
				$path = $path . DIRECTORY_SEPARATOR . $model->id;
				if (!is_dir($path)) {
					mkdir($path);
				}
				$archivos = Tools::dirToArray($path);
				if (count($archivos) > 0) :
					foreach ($archivos as $a => $archivo) {
						echo '<div class="archivo">' .
							'<input style="display:none;" type="checkbox" name="eliminar[' . $archivo . ']" class="eliminar" href="#">	' .
							'<a target="_blank" href="' . CController::createUrl("//admin/download", ["file" => $archivo, "id" => $model->id, "tipo" => "equipos_propios"]) . '">' . $archivo . '</a>' .
							'</div>';
					}
				endif; ?>
			</div>
			<br />
			<div class="row">
				<?php echo $form->labelEx($model, 'archivos'); ?>
				<?php
				$this->widget('CMultiFileUpload', array(
					'model' => $model,
					'name' => 'archivos',
					'max' => 5,
					'accept' => 'pdf|doc|docx|xls|xlsx|png|jpg|jpeg|txt|ppt|pptx',
					'duplicate' => 'Archivo ya existe',
					'denied' => 'Error: Extensión de archivo no permitida',
				));
				echo $form->error($model, 'archivos');
				?>
			</div>
		</fieldset>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Guardar', array('id' => 'guardar', 'disabled' => $model->validado == 2 ? 'disabled' : '',)); ?>
		</div>

	<?php $this->endWidget();
endif; ?>

	</div>
	<!-- form -->



	<style>
		.btn {}

		.btn:hover {
			cursor: pointer;
		}

		.archivo {
			display: inline-block;
			width: 150px;
			height: 30px;
			border: 1px solid silver;
			border-radius: 5px;
			padding: 10px;
			margin: 10px;
			max-width: 200px !important;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}

		.eliminar {
			position: relative;
			top: 5px;
			left: 0px;
		}
	</style>
	<script>
		var mostrando = false;
		$('.seleccionar').click(function(e) {
			$(".eliminar").prop('checked', false);
			if (!mostrando) {
				$('.eliminar').show();
			} else {
				$('.eliminar').hide();
			}
			mostrando = !mostrando;
		});
	</script>