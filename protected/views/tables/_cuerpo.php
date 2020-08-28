<div class="wrapper">
	<img class="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gear.gif"/>
	<table id="datos" class='display nowrap' data-order='[[ 1, "asc" ]]' style="width:100%;height:100%;display:none">
		<thead>
			<tr>
			<?php
			foreach ($cabeceras as $th) {
				if (gettype($th) == 'array') {
					$atributos = "";
					$atributos_input = "";
					$ancho = 50;
					if (isset($th['width'])) {
						$ancho = Tools::CELL_SIZES[$th['width']];
						$atributos .= "style='width:" . $ancho . "px' ";
					}
					if (isset($th['format'])) {
						if ($th['format'] == 'date') {
							$atributos_input .= "class='datepicker' ";
						}
					}
					if (isset($th['name'])) {
						echo "<th " . $atributos . " title='" . $th['name'] . "'><input style='width:" . $ancho . "px' $atributos_input type='text' placeholder='" . $th['name'] . "' /></th>";
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
					}
					if(isset($extra_dato['acumulado'])){
						$acumulado = $extra_dato['acumulado'];
						$class .= " $acumulado";
					}
					?>
					<td campo="<?=$campo?>" style='<?=$estilos?>' data-toggle data-placement class="<?=$class?>"><?=$valor?></td>
				<?php endforeach;?>
			</tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
			<tr>
				<th>Totales:</th>
				<?php for($i = 1; $i < count($extra_datos); $i++):?>
					<th class="footer_<?=$extra_datos[$i]["campo"]?>"></th>
				<?php endfor;?>
			</tr>
		</tfoot>
		
	</table>
</div>