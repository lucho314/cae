<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'SGD CAE: Nueva Categoria';
?>
<article class="col-xs-12 col-md-10">
    <h3>Crear Categoria:</h3>
    <?=$msg?>
    <hr>
    <?php
    $form = ActiveForm::begin([
        "method"=>"post",
        "id" => "formulario",
        "enableClientValidation" => false,
        "enableAjaxValidation" => true,
            ])
    ?>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <?=$form->field($model,"nombre_categoria")->input("text",["placeholder"=>"Nombre de la Categoria",'autofocus' => 'autofocus' ,"class"=>"form-control",'value'=>'hola'])?>
                
            </div>
            <div class="form-group">
                <?=$form->field($model,"edad_min")->input("text",["placeholder"=>"Edad Minima", "class"=>"form-control",'autofocus' => 'autofocus' ])?>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                 <?=$form->field($model,"edad_max")->input("text",["placeholder"=>"Edad Maxima", "class"=>"form-control",'autofocus' => 'autofocus' ])?>
            </div>
            <div class="form-group">   
                <?= $form->field($model,'deporte')->dropDownList($deporte)?>
                <?= $form->field($model,'profesor_titular')->dropDownList([$profesor])?>
                <?= $form->field($model,'profesor_suplente')->dropDownList($profesor,['prompt' => ''])?>
            </div>
            <?=Html::submitButton("Guardar",["class"=>"btn btn-primary", "style"=>"float:right"])?>
        </div>
    </div>
    <?php $form->end() ?>
</article>
