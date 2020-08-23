
<table>
	<thead>

	</thead>
	<tbody>
	<?php foreach($modelos as $modelo):?>
		<tr>
			<td><?=$modelo->user?></td>
			<td><?=$modelo->email?></td>
			<td><?=$modelo->nombre?></td>
			<td><?=$modelo->rol?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>

