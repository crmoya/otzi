<h3>Historial de validaciones realizadas al Report <?php echo CHtml::encode($report->reporte);?></h3>

<table class="table1">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Report</th>
            <th>Fecha Validación</th>
            <th>Validador</th>
            <th>Fecha Corrección Report validado</th>
            <th>Autorizador 1</th>
            <th>Autorizador 2</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $validaciones = HistorialValidacionesEp::model()->findAllByAttributes(array('rEquipoPropio_id'=>$report->id));
            foreach($validaciones as $validacion):
                $modificacion = ModEpAutorizadaPor::model()->findByAttributes(array('historial_validaciones_ep_id'=>$validacion->id));
                ?>
        <tr>
            <td><?php echo date_format(date_create($validacion->rEquipoPropio->fecha),"d/m/Y");?></td>
            <td><?php echo $validacion->rEquipoPropio->reporte;?></td>
            <td><?php echo date_format(date_create($validacion->fecha),"d/m/Y H:i ");?></td>
            <td><?php echo $validacion->usuario->nombre." (".$validacion->usuario->user.")";?></td>
            <td><?php echo $modificacion!=null?date_format(date_create($modificacion->fecha),"d/m/Y H:i "):"";?></td>
            <td><?php echo $modificacion!=null?$modificacion->usuario1->nombre." (".$modificacion->usuario1->user.")":"";?></td>
            <td><?php echo $modificacion!=null?$modificacion->usuario2->nombre." (".$modificacion->usuario2->user.")":"";?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>