<?php

use yii\widgets\ActiveForm;

$this->title = 'SGD CAE: Modificar Deporte';
?>

<article class="col-xs-12 col-sm-9 col-md-10">
    <h3>Crear Deporte:</h3>
    <?=$msg?>
    <hr>
    <?php
    $form = ActiveForm::begin([
                "method" => "post",
                "enableClientValidation" => false,
                "enableAjaxValidation"=>TRUE
            ])
    ?>
    <div class="row col-xs-12 col-md-5">
        <div class="form-group">
            <label for="seleccionar nombre del deporte a eliminar">Seleccione: </label>
            <?= $form->field($model,'deporte')->dropDownList($deporte)->label(false)?>
        </div>
        <div class="form-group">
            <label>Nombre:</label>
            <?= $form->field($model, "nombre")->input("text", ["placeholder" => "Nombre del Deporte", "class" => "form-control", "required"])->label(false) ?>
        </div>
        <input type="submit" value="Guardar" class="btn btn-primary">
    </div>
    <?php $form->end()?>
</article>
