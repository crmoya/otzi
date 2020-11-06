<?php

Yii::app()->getController()->pageTitle="Informe de Gasto de Combustibles";
?>


<?php echo $this->renderPartial('_header2', [
                                    'model' => new GastoCombustible,
                                    'fecha_inicio' => $fecha_inicio,
                                    'fecha_fin' => $fecha_fin,
                                    'propiosOArrendados' => $propiosOArrendados,
                                    'maquina' => $maquina,
                                    'operador' => $operador,
                                    'centro_gestion' => $centro_gestion,
                                    'tipoCombustible_id' => $tipoCombustible_id,]); ?>

<?php
echo $this->renderPartial('//tables/_cuerpo',['datos'=>$datos, 'cabeceras' => $cabeceras, 'extra_datos'=>$extra_datos]);
?>
<?php echo $this->renderPartial('//tables/_footer',['extra_datos'=>$extra_datos]); ?>
