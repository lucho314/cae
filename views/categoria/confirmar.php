

<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'SGD CAE: Buscar Categoria';
?>

<article class="col-xs-12 col-md-10">
    <h3>Modificar Categoria: 1/2</h3>
    <?=$msg?>
    <hr>
    <?=
    Html::beginForm(
            Url::toRoute("categoria/eliminar"), "post", ['class' => 'row col-xs-12 col-md-5']
    );
    ?>
    
    <div class="row col-xs-12 col-md-5">
        <div class="form-group">
            <?=$msg?>
            <label for="seleccionar categoria a modificar:">Seleccione:</label>
            <input type="hidden" value="<?=$id?>" name="id">
        </div>
        <input type="submit" value="confirmar" name="confirmar" class="btn btn-default">
    </div>
    
<?= Html::endForm() ?>
</article>