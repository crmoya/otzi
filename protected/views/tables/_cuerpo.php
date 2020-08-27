<div class="wrapper">
	<img class="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gear.gif"/>
	<table id="datos" class='display nowrap' data-order='[[ 1, "asc" ]]' style="width:100%;height:100%;display:none">
		<thead>
			<tr>
				<?php foreach($cabeceras as $cabecera):?>
				<th><?=$cabecera?></th>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($datos as $fila):?>
			<tr>
				<?php foreach($extra_datos as $extra_dato): 
					$campo = $extra_dato['campo'];?>
					<td <?=in_array("dots",$extra_dato)?"class='dots'":""?>><?=$fila->$campo?></td>
				<?php endforeach;?>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>