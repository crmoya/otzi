
<div id="lienzo">
<?=$html?>
</div>
<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
?>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script> 
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css"/>
<script>
    printJS({
        printable: 'lienzo',
        type: 'html',
        onPrintDialogClose: function(e){
            window.close()
        }
    });
</script>