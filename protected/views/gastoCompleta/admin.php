<?php

Yii::app()->getController()->pageTitle="Gastos de " . $gastoNombre . " de Rindegastos";
?>
<?php echo $this->renderPartial('//tables/_header', ['model'=>$model]); ?>

<?php
echo $this->renderPartial('//tables/_cuerpo',['datos'=>$datos, 'cabeceras' => $cabeceras, 'extra_datos'=>$extra_datos]);
?>
<?php echo $this->renderPartial('//tables/_footer',['extra_datos'=>$extra_datos]); ?>
