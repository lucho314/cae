<?php


use yii\widgets\ActiveForm;

$this->title = 'SGD CAE: Nueva Clase';
?>
<article class="col-xs-12 col-md-10">
    <h3>Asistencia:</h3>
    <hr>
    <?php
    $form = ActiveForm::begin([
                "method"=>"post",
                "id"=>"formulario",
                "enableClientValidation" =>false,
                "enableAjaxValidation"=>true,
        ])
    ?>
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
                    <?= $form->field($model,'comision')->dropDownList($comisiones)->label(false)?>
                </div>
            </div>
        </div>
        <div class="row col-xs-12">
            <div class="form-group">
                <label for="ingresar observaciones">Observaciones:</label>
                <?= $form->field($model,'observaciones')->textarea(['class'=>"form-control",'style'=>"resize:none;", 'rows'=>"4"])->label(false)?>
            </div>
            <input type="submit" value="Guardar" class="btn btn-primary">
        </div>
    <?php $form->end() ?>
</article>