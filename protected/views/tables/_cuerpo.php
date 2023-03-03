<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php if(isset($esquema)):?>

<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
Columnas del informe: <i class="toggle icon-plus-sign open-columns"></i>
<div class="columns">
	<div class="row">
		<div class="col-md-10">
		<?php 
		$params = Yii::app()->request->getQueryString();
		$columns = Yii::app()->request->getParam('columns');
		$i = 0;
		foreach ($esquema as $th):
			$checked = "checked";
			if(isset($columns) && $columns != ""){
				$column = Tools::charAt($columns, $i);
				$checked = $column == "1"?"checked":"";
			}
			$i++;
		?>
			<div class='column-wrapper'><input i='' class='column-check' type='checkbox' <?=$checked?> name="<?=$th?>">&nbsp;&nbsp;<?=$th?></div>
		<?php 
		endforeach; ?>
		</div>
		<div class="col-md-2">
			<div class="btn btn-info filtro-columnas">Aceptar</div>
		</div>
	</div>
</div>

<?php 
$index = strpos($params, '&columns=');
if($index !== false){
	$params = substr($params, 0, $index);
}

?>
<script>
$(document).ready(function(e){
	String.prototype.replaceAt = function(index, replacement) {
		return this.substr(0, index) + replacement + this.substr(index + replacement.length);
	}
	let open = false;
	function toggle(){
		if(!open){
			$('.columns').fadeIn();
			$('.toggle').removeClass('icon-plus-sign');
			$('.toggle').addClass('icon-minus-sign');
		}
		else{
			$('.columns').fadeOut();
			$('.toggle').removeClass('icon-minus-sign');
			$('.toggle').addClass('icon-plus-sign');
		}
		open = !open;
	}
	$('.open-columns').click(()=>{
		toggle();
	});
	let columns = '';
	let i = 0;
	$('.column-check').each(function(e){
		columns += $(this).prop('checked')?'1':'0';
		$(this).attr('i',i++);
	});
	$('.column-check').change(function(e){
		var checked = $(this).prop('checked')?'1':'0';
		if(countChecked() < 2 && checked == "0"){
			$(this).prop('checked','checked');
			Swal.fire({
				title: 'Error!',
				text: 'No es posible mostrar menos de 2 columnas del informe',
				icon: 'error',
				confirmButtonText: 'OK'
			});
			return false;
		}
		var i = parseInt($(this).attr('i'));
		columns = columns.replaceAt(i, checked);
	});

	$('.filtro-columnas').click(function(e){
		window.location = '<?=CController::createUrl(Yii::app()->controller->id."/".Yii::app()->controller->action->id)."?".$params?>&columns=' + columns;
	});

	function countChecked(){
		var checked = 0;
		$('.column-check').each(function(e){
			if($(this).prop('checked')){
				checked++;
			}
		});
		return checked;
	}
});
</script>
<?php endif;?>
<style>
.column-wrapper{
	width: 250px;
	display: inline-block;
}
.open-columns:hover{
	cursor: pointer;
}
.columns{
	display: inline-block;
	border: 1px solid silver;
	border-radius: 10px;
	padding: 10px;
	display: none;
}
</style>
<div class="wrapper">
	<img class="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/Gear.gif"/>
	<table id="datos" class='display nowrap' data-order='[[ 1, "desc" ]]' style="width:100%;height:100%;display:none">
		<thead>
			<tr>
			<?php
			foreach ($cabeceras as $th) {
				if (gettype($th) == 'array') {
					$atributos_input = "";
					$ancho = 50;
					$style = "style='";
					if (isset($th['width'])) {
						switch ($th['width']) {
							case 'xs':
								$ancho = Tools::$XS_CELL;
								break;
							case 'sm':
								$ancho = Tools::$SM_CELL;
								break;
							case 'md':
								$ancho = Tools::$MD_CELL;
								break;
							case 'lg':
								$ancho = Tools::$LG_CELL;
								break;
							case 'xl':
								$ancho = Tools::$XL_CELL;
								break;
							default:
								break;
						}
						$style .= "width:" . $ancho . "px;";
					}
					if (isset($th['format'])) {
						if ($th['format'] == 'date') {
							$atributos_input .= "class='datepicker' ";
						}
					}
					if (isset($th['visible'])) {
						if ($th['visible'] == 'false') {
							$style .= "display: none;";
						}
					}
					$style .= "'";
					if (isset($th['filtro'])) {
						if($th['filtro']=='false'){
							echo "<th>" . $th['name'] . "</th>";
						}
						if ($th['filtro'] == 'checkbox') {
							echo "<th><img src='" . Yii::app()->request->baseUrl . "/images/check_old.png' class='select-all'/><span>" . $th['name'] . "</span></th>";
						}
						if($th['filtro']=='validacion'){
							echo "<th><img src='" . Yii::app()->request->baseUrl . "/images/check_old.png' class='validate-all'/><span>" . $th['name'] . "</span></th>";
						}
					}
					else{
						echo "<th " . $style . " title='" . $th['name'] . "'><input style='width:" . $ancho . "px' $atributos_input type='text' placeholder='" . $th['name'] . "' /></th>";						
					}
					
				}
			}
			?>
			
			</tr>
		</thead>
		<tbody>
		<?php 
			$totales = [];
			foreach($datos as $fila):
			?>
			<tr>
				<?php foreach($extra_datos as $i => $extra_dato): 
					$campo = $extra_dato['campo'];
					$estilos = "";
					$valor = $fila->$campo;
					$class = "";
					if(isset($extra_dato['dots'])){
						$tamaño = $extra_dato['dots'];
						$class .= "dots-$tamaño ";
					}
					if(isset($extra_dato['format'])){
						if($extra_dato['format'] == "money"){
							$estilos .= "text-align:right;";
							$valor = "$".number_format((int)$fila->$campo,0,"",".");
						}
						if($extra_dato['format'] == "validado"){
							$estilos .= "text-align:center;";
							$valor_id = -1;
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$valor_id = $fila->$param;
								}
							}
							if($fila->$campo == 1){
								$valor = "<img id_reg='" . $valor_id . "' class='validate validar-2' src='" . Yii::app()->request->baseUrl . "/images/check.png'><span class='validar-2' style='display:none;'>1</span>";
							}
							else if($fila->$campo == 2){
								$valor = "<img id_reg='" . $valor_id . "' class='full-validado' src='" . Yii::app()->request->baseUrl . "/images/check2.png'><span class='full-validado' style='display:none;'>2</span>";
							}
							else{
								$valor = "<img id_reg='" . $valor_id . "' class='validate validar-1' src='" . Yii::app()->request->baseUrl . "/images/eliminar.png'><span class='validar-1' style='display:none;'>0</span>";
							}
						}
						if($extra_dato['format'] == "number"){
							$estilos .= "text-align:right;";
							$valor = number_format((float)$fila->$campo,"0","",".");
						}
						if($extra_dato['format'] == "decimal1"){
							$estilos .= "text-align:right;";
							$valor = number_format((float)$fila->$campo,"1",",",".");
						}
						if($extra_dato['format'] == "decimal2"){
							$estilos .= "text-align:right;";
							$valor = number_format((float)$fila->$campo,"2",",",".");
						}
						if($extra_dato['format'] == "decimal3"){
							$estilos .= "text-align:right;";
							$valor = number_format((float)$fila->$campo,"3",",",".");
						}
						if($extra_dato['format'] == "imagen"){
							$valor = '<a target="_blank" href="' . $fila->$campo . '"><img src="' . Yii::app()->request->baseUrl . '/images/search.png"></a>';
						}
						if($extra_dato['format'] == "imagen-gasto"){
							if($fila->$campo != ""){
								$valor = '<a target="_blank" href="' . $fila->$campo . '"><img src="' . Yii::app()->request->baseUrl . '/images/search.png"></a>';
							}
							else{
								$valor = "SIN IMAGEN";
							}
						}
						if ($extra_dato["format"] == "delete") {
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							$newpage = "";
							if(isset($extra_dato['new-page'])){
								if($extra_dato['new-page'] == 'true'){
									$newpage = "target ='_blank'";
								}
							}
							$valor = '<button class="delete-gasto" url="' . CController::createUrl($extra_dato['url'])
							. '?' . $params .'"><i class="fa fa-trash text-danger" aria-hidden="true"></i></button>';
							// $valor = '<a class="btn seleccionar" href="' . $fila->$campo . '"><img src="' . Yii::app()->request->baseUrl . '/images/basura.png"></a>';
						}
						if($extra_dato['format'] == "enlace"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							$newpage = "";
							if(isset($extra_dato['new-page'])){
								if($extra_dato['new-page'] == 'true'){
									$newpage = "target ='_blank'";
								}
							}
							$valor = '<a valor="' . $valor . '" ' . $newpage . ' href="' . CController::createUrl($extra_dato['url']) . '?' . $params .'">' . $valor . '</a>';
						}
						if($extra_dato['format'] == "enlace-documento"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							$newpage = "";
							if(isset($extra_dato['new-page'])){
								if($extra_dato['new-page'] == 'true'){
									$newpage = "target ='_blank'";
								}
							}
							$valor = '<input value="' . $fila->$campo . '" type="checkbox" class="check-adjunto"/>&nbsp;<a ' . $newpage . ' href="' . CController::createUrl($extra_dato['url']) . '?' . $params .'"><img src="' . Yii::app()->request->baseUrl . '/images/txt-chico.png"></a>';
						}
						if($extra_dato['format'] == "enlace-imagen"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							if(isset($extra_dato['fecha_inicio'])){
								$params .=  "fecha_inicio=" . $extra_dato['fecha_inicio'] . "&";
							}
							if(isset($extra_dato['fecha_fin'])){
								$params .=  "fecha_fin=" . $extra_dato['fecha_fin'] . "&";
							}
							$newpage = "";
							if(isset($extra_dato['new-page'])){
								if($extra_dato['new-page'] == 'true'){
									$newpage = "target ='_blank'";
								}
							}
							$valor = '<a ' . $newpage . ' href="' . CController::createUrl($extra_dato['url']) . '?' . $params .'"><img src="' . Yii::app()->request->baseUrl . '/images/search.png"></a>';
						}
						if($extra_dato['format'] == "enlace_rg"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							$valor = '<a target="_blank" href="' . CController::createUrl($extra_dato['url']) . '?' . $params .'">' . $valor . '</a>';
						}
						if($extra_dato['format'] == "enlace-ver"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$valor = str_replace("\"","___",$fila->$param);
									$params .= $param . "=" . $valor . "&";
								}
							}
							$valor = '<a href="' . CController::createUrl($extra_dato['url']) . '&' . $params .'"><img src="' . Yii::app()->request->baseUrl . '/images/search.png"/></a>';	
						}
						if($extra_dato['format'] == "date"){
							$estilos .= "text-align:center;";
							$valor = Tools::backFecha($valor);
						}
					}
					if(isset($extra_dato['visible'])){
						if($extra_dato['visible'] == "false"){
							$estilos .= "display:none;";
						}
					}
					?>
					<td campo="<?=$campo?>" style='<?=$estilos?>' data-toggle data-placement class="<?=$class?>"><?=$valor?></td>
				<?php endforeach;?>
			</tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
			<tr>
				<th>Total filtrado:<br/>Total general:</th>
				<?php for($i = 1; $i < count($extra_datos); $i++):?>
					<th style="text-align:right;"></th>
				<?php endfor;?>
			</tr>
		</tfoot>
		
	</table>
</div>
<script>
	$(document).ready(function() {
		$('.delete-gasto').click(function(e){
			var url = $(this).attr("url");
			Swal.fire({
				title: "Eliminar Gasto",
				text: "¿Está seguro de eliminar este gasto?",
				// icon: 'info',
				showConfirmButton: true,
				showDenyButton: true,
				// showCancelButton: true,
				confirmButtonText: 'Borrar',
				denyButtonText: 'Cancelar',
				dangerMode: true,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						type: "POST",
						url: url,
					}).done(function(msg) {
						var respuesta = JSON.parse(msg);
						console.log(respuesta);
						if (respuesta != 'SUCCESS') {
							Swal.fire({
								title: "ERROR",
								text: "No se pudo eliminar el gasto. Error: " + respuesta.message,
								icon: 'error',
							});
						}
						else{
							Swal.fire({
								title: "Registro Gasto Eliminado",
								text: "Se eliminó el gasto exitosamente",
								icon: 'success',
							});
							location.reload();
						}
					});
				} else {
					Swal.fire({
						title: "Acción Cancelada",
						text: "Se canceló la eliminación del Gasto",
						icon: 'info',
					});
				}
			})

		});
	});
</script>