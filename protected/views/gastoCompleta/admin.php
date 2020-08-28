<?php

Yii::app()->getController()->pageTitle="Gastos de " . $gastoNombre;
?>

<h3>Registros de gastos de <?=$gastoNombre?></h3>

<?php echo $this->renderPartial('//tables/_header', ['model'=>$model]); ?>

<?php
$datos = GastoCompleta::model()->findAll($model->search());
echo $this->renderPartial('//tables/_cuerpo',['datos'=>$datos, 'cabeceras' => $cabeceras, 'extra_datos'=>$extra_datos]);
?>
<?php echo $this->renderPartial('//tables/_footer',['extra_datos'=>$extra_datos]); ?>
