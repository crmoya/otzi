<?php

$ruta = "";
$nombre = "";
switch ($tipo) {
    case 'camiones_propios':
        $ruta = "informeRegExpCamionPropio/admin";
        $nombre = "Expediciones de Camiones Propios";
        break;
    case 'camiones_arrendados':
        $nombre = "Expediciones de Camiones Arrendados";
        $ruta = "informeRegExpCamionArrendado/admin";
        break;
    case 'equipos_propios':
        $nombre = "Expediciones de Equipos Propios";
        $ruta = "informeRegExpEquipoPropio/admin";
        break;
    case 'equipos_arrendados':
        $nombre = "Expediciones de Equipos Arrendados";
        $ruta = "informeRegExpEquipoArrendado/admin";
        break;   
    default:
        # code...
        break;
}

$this->breadcrumbs=array(
	$nombre=>array($ruta),
	"Adjuntos del Report ".$report->reporte
);

?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<div class="row">
    <?php
    $path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR . $tipo . DIRECTORY_SEPARATOR . $report->id;
    $archivos = Tools::dirToArray($path);
    foreach ($archivos as $a => $archivo) :
        if (file_exists($path . DIRECTORY_SEPARATOR . $archivo)) {
            $extension = strtolower(pathinfo($path . DIRECTORY_SEPARATOR . $archivo)['extension']);
            $icono = "file.png";
            switch ($extension) {
                case 'pdf':
                    $icono = "pdf.png";
                    break;
                case 'doc':
                    $icono = "word.png";
                    break;
                case 'docx':
                    $icono = "word.png";
                    break;
                case 'xls':
                    $icono = "xls.png";
                    break;
                case 'xlsx':
                    $icono = "xls.png";
                    break;
                case 'ppt':
                    $icono = "ppt.png";
                    break;
                case 'pptx':
                    $icono = "ppt.png";
                    break;
                case 'png':
                    $icono = "png.png";
                    break;
                case 'jpg':
                    $icono = "jpg.png";
                    break;
                case 'jpeg':
                    $icono = "jpg.png";
                    break;
                case 'txt':
                    $icono = "txt.png";
                    break;
                default:
                    $icono = "file.png";
                    break;
            }
        }
        $imagen = Yii::app()->baseUrl."/images/".$icono;
    ?>
        <div class="archivo col-md-2">
            <img class="imagen" src="<?=$imagen?>"/><br/>
            <a target="_blank" href="<?= CController::createUrl("//admin/download", ['file' => $archivo, 'id' => $report->id, 'tipo' => $tipo]); ?>"><?= $archivo ?></a>
        </div>
    <?php endforeach; ?>
</div>
<style>
.archivo{
    text-align: center;
}
.imagen{
    width: 80px;
    height: 90px;
    margin:10px;
    border: 1px solid gray;
    border-radius: 5px;
    padding: 10px;
}
</style>