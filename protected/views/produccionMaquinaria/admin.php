<?php

Yii::app()->getController()->pageTitle="Informe de Producción de Maquinaria";
?>


<?php echo $this->renderPartial('_header', ['model'=>$model]); ?>

<?php
echo $this->renderPartial('//tables/_cuerpo',['datos'=>$datos, 'cabeceras' => $cabeceras, 'extra_datos'=>$extra_datos, 'esquema' => $esquema]);
?>
<?php echo $this->renderPartial('//tables/_footer',['extra_datos'=>$extra_datos]); ?>
