<?php
use yii\widgets\ActiveForm;

$this->title = 'SGD CAE: Nueva Categoria';
?>
<article class="col-xs-12 col-md-10">
    <h3>Crear Categoria:</h3>
    <?=$msg?>
    <hr>
    <?php
    $form = ActiveForm::begin([
                "id"=>"formulario",
                "enableClientValidation" =>false,
                "enableAjaxValidation"=>true,
                "method"=>"post"
            ])
    ?>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="ingresar nombre categoria:">Nombre:</label>
                <?=$form->field($model,"nombre_categoria")->input("text",["placeholder"=>"Nombre de la Categoria","class"=>"form-control", "autofocus"])->label(false)?>
            </div>
            <div class="form-group">
                <label for="ingresar edad minima">Edad Minima:</label>
                <?=$form->field($model,"edad_min")->input("number",["placeholder"=>"Edad Minima","class"=>"form-control"])->label(false)?>
            </div>
            <div class="form-group">
                <label for="ingresar edad maxima">Edad Maxima:</label>
                <?=$form->field($model,"edad_max")->input("number",["placeholder"=>"Edad Maxima","class"=>"form-control"])->label(false)?>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="seleccione un profesor titular">Profesor Titular:</label>
                <?=$form->field($model,'profesor_titular')->dropDownList($profesor)->label(false)?>
            </div>
            <div class="form-group">
                <label for="seleccione un profesor suplente">Profesor Suplente:</label>
                <?=$form->field($model,'profesor_suplente')->dropDownList($profesor,['prompt'=>''])->label(false)?>
            </div>
            <div class="form-group">
                <label for="seleccione un deporte">Deporte:</label>
                <?=$form->field($model,'deporte')->dropDownList($deporte)->label(false)?>
            </div>
            <input type="submit" value="Guardar" class="btn btn-primary" style="float:right;">
        </div>
    </div>
    <?php $form->end() ?>
</article>

