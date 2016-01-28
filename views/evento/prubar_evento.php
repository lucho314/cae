<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'SGD CAE: Nuevo Profesor';
?>

<article class="col-xs-12 col-md-10">
    <h3>Crear evento 2/2</h3>
    <?=$msg?>
    <hr>
    <?php
    $form = ActiveForm::begin([
                "method"=>"post",
                "id"=>"formulario",
                "enableClientValidation" =>false,
                "enableAjaxValidation"=>true,
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

            <div class="form-group">
                <label for="profesor_titular">prof_titul:</label>
                  <?=$form->field($model,"id_profesor_titular")->dropDownList($profesor,['size'=>'6','class'=>"form-control"])->label(false)?>
            </div>

            <div class="form-group">
                <label for="profesor_suplente">prof_suplente:</label>
                  <?=$form->field($model,"id_profesor_suplente")->dropDownList($profesor,['size'=>'6','class'=>"form-control"])->label(false)?>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="deporte">deporte:</label>
                <?=$form->field($model,"id_deporte")->dropDownList($deporte,['size'=>'6','class'=>"form-control"])->label(false)?>
            </div>

            <div class="form-group">
                <label for="categoria">categoria:</label>
                <?=$form->field($model,"id_categoria")->dropDownList($categoria,['size'=>'6','class'=>"form-control"])->label(false)?>
            </div>
            
            

            <div class="form-group">
                <label for="listaa">lista:</label>
                 <?=$form->field($model,"id_lista")->dropDownList($convocados,['size'=>'6','class'=>"form-control"])->label(false)?>

            </div>


            
          
            <div class="botones">
                <input type="submit" value="Guardar" class="btn btn-primary">
                <a href="<?= Url::toRoute("evento/crear") ?>" class="btn btn-default">Atras</a>
            </div>
        </div>
    </div>
    <?php $form->end() ?>
</article>
