<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

<h1>Formulario</h1>
<?=$msg?>
<?= Html::beginForm(
        Url::toRoute("usuario/nuevo"),//action
        "get",//method
        ['class' => 'form-inline']//options
        );
?>

<div class="form-group">
    <?= Html::label("Introduce tu nombre", "nombre") ?>
    <?= Html::textInput("nombre", null, ["class" => "form-control"]) ?>
</div>

<?= Html::submitInput("Enviar nombre", ["class" => "btn btn-primary"]) ?>

<?= Html::endForm() ?>