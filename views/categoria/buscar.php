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
            Url::toRoute("categoria/modificar"), "post", ['class' => 'row col-xs-12 col-md-5']
    );
    ?>
    
    <div class="row col-xs-12 col-md-5">
        <div class="form-group">
            <label for="seleccionar categoria a modificar:">Seleccione:</label>
            <?= Html::dropDownList('id_categoria',NULL,$opciones,['prompt'=>'----Categorias----'])?>
        </div>
        <input type="submit" value="Continuar" class="btn btn-default">
    </div>
    
<?= Html::endForm() ?>
</article>
