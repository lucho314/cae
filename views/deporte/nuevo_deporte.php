<?php

use yii\widgets\ActiveForm;

$this->title = 'SGD CAE: Nuevo Deporte';
?>

<article class="col-xs-12 col-md-10">
    <h3>Crear Deporte:</h3>
    <?= $msg ?>
    <hr>
    <?php
    $form = ActiveForm::begin([
                "method" => "post",
                "id" => "formulario",
                "enableClientValidation" => false,
                "enableAjaxValidation" => true,
    ]);
    ?>
    <div class="row col-xs-12 col-md-5">
        <div class="form-group">
            <label for="ingresar nombre del deporte">Nombre: </label>
            <?= $form->field($model, "nombre_deporte")->input("text", ["placeholder" => "Nombre del Deporte", "class" => "form-control","autofocus"])->label(false) ?>
        </div>
        <input type="submit" value="Guardar" class="btn btn-primary">
    </div>
    <?php $form->end() ?>
</article>

