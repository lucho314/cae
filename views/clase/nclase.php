<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'SGD CAE: Nueva Clase';
?>
<head>
    <script type="text/javascript" src="../web/js/menu.js"></script>
</head>
<article class="col-xs-12 col-md-10">

    <?php
    $form = ActiveForm::begin([
                "method" => "post",
                "id" => "formulario",
                "enableClientValidation" => false,
                "enableAjaxValidation" => true,
            ])
    ?>
        <h3>Asistencia:</h3>
        <hr>
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label for="ingresar fecha de la clase">Fecha:</label>
                    <?= $form->field($model, "fecha")->input("date", ["placeholder" => "Nombre del Deporte", "class" => "form-control", "autofocus"])->label(false) ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label for="seleccionar comision">Comision:</label>
                    <?= $form->field($model, 'comision')->dropDownList($comisiones)->label(false) ?>
                </div>
            </div>
        </div>
        <div class="row col-xs-12">
            <div class="form-group">
                <label for="ingresar observaciones">Observaciones:</label>
                <?= $form->field($model, 'observaciones')->textarea(['class' => "form-control", 'style' => "resize:none;", 'rows' => "4"])->label(false) ?>
            </div>
        </div>
        <?= Html::submitButton("continuar",["class"=>"btn btn-primary"]);?>
    <?php $form->end() ?>
</article>