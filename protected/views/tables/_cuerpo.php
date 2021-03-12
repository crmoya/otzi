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
							$valor = '<a ' . $newpage . ' href="' . CController::createUrl($extra_dato['url']) . '?' . $params .'">' . $valor . '</a>';
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
									$params .= $param . "=" . $fila->$param . "&";
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
				<th>Total página:<br/>Total general:</th>
				<?php for($i = 1; $i < count($extra_datos); $i++):?>
					<th style="text-align:right;"></th>
				<?php endfor;?>
			</tr>
		</tfoot>
		
	</table>
</div>