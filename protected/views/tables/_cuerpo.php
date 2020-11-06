<div class="wrapper">
	<img class="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gear.gif"/>
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
					if (isset($th['name'])) {
						if ($th['name'] == 'Ver') {
							echo "<th " . $style . " title='" . $th['name'] . "'> Ver </th>";
						}
						else{
							echo "<th " . $style . " title='" . $th['name'] . "'><input style='width:" . $ancho . "px' $atributos_input type='text' placeholder='" . $th['name'] . "' /></th>";
						}
					}
				}
			}
			?>
			
			</tr>
		</thead>
		<tbody>
		<?php 
			$totales = [];
			foreach($datos as $fila):?>
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
							$valor = "$".number_format((int)$fila->$campo,"0","",".");
						}
						if($extra_dato['format'] == "number"){
							$estilos .= "text-align:right;";
							$valor = number_format((int)$fila->$campo,"0","",".");
						}
						if($extra_dato['format'] == "imagen"){
							$valor = '<a target="_blank" href="' . $fila->$campo . '"><img src="' . Yii::app()->request->baseUrl . '/images/search.png"></a>';
						}
						if($extra_dato['format'] == "enlace"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							$valor = '<a href="' . CController::createUrl($extra_dato['url']) . '?' . $params .'">' . $valor . '</a>';
						}
						if($extra_dato['format'] == "enlace-ver"){
							$params = "";
							if(isset($extra_dato['params'])){
								foreach($extra_dato['params'] as $param){
									$params .= $param . "=" . $fila->$param . "&";
								}
							}
							$valor = '
							<a href="' . CController::createUrl($extra_dato['url']) . '&' . $params .'"><img src="' . Yii::app()->request->baseUrl . '/images/search.png"/></a>';
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