<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'SGD CAE: Modificar Deporte';
?>

<article class="col-xs-12 col-sm-9 col-md-10">
    <h3>Modificar Deporte:</h3>
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
            <label>Nombre:</label>
            <?= $form->field($model, "nombre_deporte")->input("text", ["placeholder" => "Nombre del Deporte", "class" => "form-control", "autofocus"=>true])->label(false) ?>
        </div>
        <?=$form->field($model,"deporte")->input("hidden")->label(false)?>
        <input type="submit" value="Guardar" class="btn btn-primary">
        <a href="<?=Url::toRoute("deporte/buscar")?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span>Atras</a>
    </div>
    <?php $form->end()?>
</article>
