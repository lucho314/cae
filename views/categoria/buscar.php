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
            Url::toRoute("categoria/modificar"), "post", ['class' => 'row col-xs-12 col-md-4']
    );
    ?>
    <div class="form-group">
        <label for="seleccionar categoria a modificar:">Seleccione:</label>
        <?= Html::dropDownList('id_categoria', NULL, $opciones, ['autofocus', 'class' => 'form-control']) ?>
    </div>
    <button type="submit" class="btn btn-default">
        Continuar
        <span class="glyphicon glyphicon-chevron-right"></span>
    </button>
    <?= Html::endForm() ?>
</article>

