<?php

use yii\widgets\ActiveForm;

$this->title = 'SGD CAE: Nueva Comision';
?>

<article class="col-xs-12 col-md-10">
    <h3>Crear Comisión:</h3>
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
    <div class="row">
        <div class="col-xs-12 col-md-3">
            <div class="form-group">
                <label for="ingresar nombre">Nombre:</label>
                <?= $form->field($model, "nombre_comision")->input("text", ['placeholder' => "Nombre de la Comision", "autofocus"])->label(false) ?>
            </div>
        </div>

        <div class="col-xs-12 col-md-3">
            <div class="form-group">
                <label for="seleccionar categoria:">Seleccione Categoria:</label>
               <?= $form->field($model,'categoria')->dropDownList($opciones,['class' => "form-control",'prompt'=>'----Categorias----'])->label(false)?>
            </div>
        </div>

        <div class="col-xs-12 col-md-2">
            <div class="form-group">
                <label for="seleccionar dia:">Seleccione Dia:</label>
                <?php
                $a = ['Lunes' => 'Lunes', 'Martes' => 'Martes', 'Miercoles' => 'Miercoles', 'Jueves' => 'Jueves', 'Viernes' => 'Viernes']
                ?>
                <?= $form->field($model, 'dia')->dropDownList($a, ['class' => "form-control"])->label(false) ?>
            </div>
        </div>

        <div class="col-xs-12 col-md-2">
            <div class="form-group">
                <label for="ingresar hora inicio">Hora de Inicio:</label>
                <?= $form->field($model, 'hora_inicio')->input("text", ['class' => "form-control"])->label(false) ?>
            </div>
        </div>

        <div class="col-xs-12 col-md-2">
            <div class="form-group">
                <label for="ingresar hora fin">Hora de Fin</label>
                <?= $form->field($model, 'hora_fin')->input("text", ['class' => "form-control"])->label(false) ?>
            </div>
            <input type="submit" value="Guardar" class="btn btn-primary" style="float:right;">
        </div>
    </div>
    <?php $form->end() ?>
</article>

