<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'SGD CAE: Nuevo Profesor';
?>

<article class="col-xs-12 col-md-10">
    <h3>Crear evento 2/2</h3>
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
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="ingresar nombre:">Nombre:</label>
                <?= $form->field($model, "nombre")->input("text", ["placeholder" => "Nombre de evento", "class" => "form-control"])->label(false) ?>   
            </div>

            <div class="form-group">
                <label for="ingresar condicion:">condicion:</label>
                <?= $form->field($model, "condicion")->input("text", ["placeholder" => "condicion", "class" => "form-control"])->label(false) ?>   
            </div>

            <div class="form-group">
                <label for="fecha">fecha:</label>
                <?= $form->field($model, "fecha")->input("date", ["placeholder" => "fecha", "class" => "form-control"])->label(false) ?>   
            </div>
        </div>
        <div class="col-xs-12 col-md-6">  
            <div class="form-group">
                <label for="profesor_titular">prof_titul:</label>
                <?= $form->field($model, "id_profesor_titular")->dropDownList($profesor, ['class' => "form-control"])->label(false) ?>
            </div>

            <div class="form-group">
                <label for="profesor_suplente">prof_suplente:</label>
                <?= $form->field($model, "id_profesor_suplente")->dropDownList($profesor, ['class' => "form-control"])->label(false) ?>
            </div>


            <div class="form-group">
                <label for="seleccionar deporte">Deporte:</label>
                <?= $form->field($model, "id_deporte")->dropDownList($deporte, ['class' => "form-control"])->label(false) ?>
            </div>
            <?= $form->field($model, 'id_evento')->input("hidden")->label(false); ?>
            <?= $form->field($model, 'convocados')->input("hidden")->label(false); ?>


            <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"]); ?>
            

        </div>

    </div>
</div>
<?php $form->end() ?>
</article>