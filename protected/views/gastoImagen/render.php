<div class="row frame">
    <div class="col-md-12">
    <?php
    $gastoImagen = GastoImagen::model()->findByPk($id);
    if(isset($gastoImagen)){
        echo $gastoImagen->file_name;
    }
    ?>
    </div>
</div>
<style>
    .frame{ 
        border: 1px solid silver;
        background: #F2F2F2;
    }
</style>