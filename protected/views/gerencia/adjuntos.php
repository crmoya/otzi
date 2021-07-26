<h5>
Exportar adjuntos de
<?php
$folder = "";
switch ($tipo) {
    case 'CA':
        echo " camiones arrendados";
        $folder = "camiones_arrendados";
        break;
    case 'CP':
        echo " camiones propios";
        $folder = "camiones_propios";
        break;
    case 'EA':
        echo " equipos arrendados";
        $folder = "equipos_arrendados";
        break;
    case 'EP':
        $folder = "equipos_propios";
        echo " equipos propios";
        break;
    default:
        break;
}
?>
</h5>
<p>
    Seleccione los adjuntos que desea exportar a PDF y presione "Descargar"
    <button id="descargar" class="btn btn-success float-right">Descargar</button>
</p>
<p>
    <button id="seleccionar" class="btn btn-info float-left">Seleccionar Todos</button><br/>
</p>
<?php
$idsArr = explode("-",$ids);
foreach($idsArr as $id):?>
<fieldset style="background:#EFEFEF;border-radius:5px;border:1px solid silver;">
    <legend>&nbsp;&nbsp;Report <?=$id?>&nbsp;&nbsp;</legend>
    <br/>
    <div class="row" style="padding-bottom:20px;margin-top:-20px;">
    <?php
    $path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $id;
    $archivos = Tools::dirToArray($path);
    foreach ($archivos as $a => $archivo) :
        $extension = "";
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
            <?php if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'):?>
                <input name="seleccionar[]" type="checkbox" report="<?=$id?>" archivo="<?=$archivo?>" class="seleccionar" href="#">
            <?php endif;?>
            <img class="imagen" src="<?=$imagen?>"/>
            <br/>
            <?= $archivo ?>
        </div>
    <?php endforeach; ?>
    </div>
</fieldset>
<?php endforeach;?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<style>
.archivo{
    text-align: center;
}
.imagen{
    width: 45px;
    height: 50px;
    margin:5px;
    border: 1px solid gray;
    border-radius: 5px;
    padding: 10px;
}
</style>

<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
?>
<script>
    var seleccionados = false;
    $('#seleccionar').click(function(e) {
        if(!seleccionados){
            $('.seleccionar').each(function(e){
                $(this).prop('checked',true);
            });
            seleccionados = true;
            $('#seleccionar').html("Deseleccionar todos");
        }
        else{
            $('.seleccionar').each(function(e){
                $(this).prop('checked',false);
            });
            seleccionados = false;
            $('#seleccionar').html("Seleccionar todos");
        }
    });
    $('#descargar').click(function(e) {
        var registros = "";
        $('.seleccionar').each(function() { 
            if($(this).prop('checked')){
                registros += $(this).attr('report')+"_!!_"+$(this).attr('archivo')+"*__*";
            }
        });
        if(registros != ""){
            registros = registros.substring(0,registros.length-4);
            var url = "<?=CController::createUrl("//gerencia/generapdf");?>?ids="+registros+"&tipo=<?=$tipo?>";
            const link = document.createElement('a');
            link.id = 'someLink';
            link.href = url;
            link.target = "_blank";
            link.click();
        }
    });
</script>