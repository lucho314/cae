<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<article class="col-xs-12 col-sm-9 col-md-10">
    <h3>Crear usuario Administrador: 2/2</h3>
    <p><?=$msg?></p>
    <hr>
<?php $form = ActiveForm::begin([
    "method"=>"post",
    "id" => "formulario",
    "enableClientValidation" => false,
    "enableAjaxValidation" => true,
]);
?>
    <div class="col-xs-12 col-md-6">
<div class="form-group">
 <?= $form->field($model, "nombre")->input("text",["placeholder"=>"Nombre de Usuario","class"=>"form-control"]) ?>   
</div>

<div class="form-group">
 <?= $form->field($model, "apellido")->input("text",["placeholder"=>"Apellido","class"=>"form-control"]) ?>   
</div>

<div class="form-group">
 <?= $form->field($model, "dni")->input("text",["placeholder"=>"DNI","class"=>"form-control"]) ?>   
</div>

<div class="form-group">
 <?= $form->field($model, "domicilio")->input("text",["placeholder"=>"Domicilio","class"=>"form-control"]) ?>   
</div>

<div class="form-group">
 <?= $form->field($model, "telefono")->input("text",["placeholder"=>"Telefono eje:3434678950","class"=>"form-control"]) ?>   
</div>
    </div>
   <div class="col-xs-12 col-md-6">  
<div class="form-group">
 <?= $form->field($model, "nombre_usuario")->input("text",["placeholder"=>"Nombre de Usuario eje: pepito","class"=>"form-control"]) ?>   
</div>

<div class="form-group">
 <?= $form->field($model, "contrasenia")->input("text",["placeholder"=>"Contraseña","class"=>"form-control"]) ?>   
</div>

<div class="form-group">
 <?= $form->field($model, "email")->input("email",["placeholder"=>"Email","class"=>"form-control"]) ?>   
</div>

<?= Html::submitButton("Enviar", ["class" => "btn btn-primary"]) ?>
   </div>
<?php $form->end() ?>
</article>