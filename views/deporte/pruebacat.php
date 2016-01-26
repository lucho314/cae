<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<article class="col-md-10">
<h1>Formulario</h1>
<?= Html::beginForm(
        Url::toRoute("deporte/elegir"),//action
        "get",//method
        ['class' => 'form-inline']//options
        );
?>

<div class="form-group">
    <?=  Html::dropDownList($profesor)?>
    <?= Html::input("text","dni")?>
</div>

<?= Html::submitInput("Enviar nombre", ["class" => "btn btn-primary"]) ?>

<?= Html::endForm() ?>
</article>