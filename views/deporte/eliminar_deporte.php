<?php


use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'SGD CAE: Eliminar Deporte';
?>

<article class="col-xs-12 col-sm-9 col-md-10">
    <h3>Eliminar Deporte:</h3>
    <?=$msg?>
    <hr>
    <?=
        Html::beginForm(
        Url::toRoute("deporte/eliminar"), "post"
    );
    ?>
    <div class="row col-xs-12 col-md-5">
        <div class="form-group">
            <label for="seleccionar nombre del deporte a eliminar">Seleccione: </label>
            <select class="form-control" autofocus name="id_deporte">
                <?php foreach ($deporte as $valor) { ?>
                    <option value="<?= $valor['id_deporte'] ?>"><?= $valor["nombre"] ?> </option >
                <?php } ?>
            </select>
        </div>
        <input type="submit" value="Eliminar" class="btn btn-primary">
    </div>
    <?= Html::endForm() ?>
</article>
