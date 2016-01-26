<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'SGD CAE: Nuevo Usuario';
?>
<article class="col-xs-12 col-md-10">
    <h3>Crear usuario: 1/2</h3>
    <hr>
    <?=
    Html::beginForm(
            Url::toRoute("usuario/nuevo"),
            "post",
            ['class' => 'row col-xs-12 col-md-5']
    );

    ?>
    <div class="form-group">
        <label for="seleccionar tipo de usuario">Seleccione:</label>
        <select class="form-control" name="tipo" autofocus>
            <option value="1">Administrador</option>
            <option value="2">Subcomisión</option>
            <option value="3">Profesor</option>
        </select>
    </div>
    <input type="submit" value="Continuar" class="btn btn-default">
<?= Html::endForm() ?>
</article>
