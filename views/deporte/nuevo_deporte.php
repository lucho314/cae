<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<article class="col-xs-12 col-sm-9 col-md-10">
    <h3>Crear Deporte:</h3>
    <?=$msg?>
    <hr>
    <?php
        $form=ActiveForm::begin([
            "id"=> "formulario",
            "class"=>"row col-xs-12 col-md-5",
            "enableClientValidation" => false,
            "enableAjaxValidation" => true,
            "method"=>"post",
        ])
    ?>
        <div class="form-group">
            <label>Nombre del deporte:</label>
            <?=$form->field($model,"nombre")->input("text",["placeholder"=>"Nombre del Deporte","class"=>"form-control","required","autofocus"])->label(false)?>
        </div>
        <input type="submit" value="Guardar" class="btn btn-primary">
    <?php $form->end()?>
</article>

