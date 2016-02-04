<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
?>
<article class="col-xs-12 col-md-10"> 

    <a href="<?= Url::toRoute("site/create") ?>">Crear un nuevo alumno</a>
    <?php
    $f = ActiveForm::begin([
                "method" => "post",
                "action" => Url::toRoute("evento/buscar"),
                "enableClientValidation" => true,
    ]);
    ?>
    <div class="form-group">
        <label>Buscar:</label>
        <?= $f->field($form, "q")->input("search")->label(false) ?>
    </div>
    <div class="form-group">
        <label>desde:</label>
        <?= $f->field($form, "desde")->input("search_desde")->label(false) ?>
    </div>
    <div class="form-group">
        <label>hasta:</label>
        <?= $f->field($form, "hasta")->input("search_hasta")->label(false) ?>
    </div>

    <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>

    <?php $f->end() ?>

    <div class="row">
        <div class="col-xs-12">
            <h3>Lista de Deportistas</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead Style="background-color:#4682B4; color:white;">
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                    <th>Ver lista</th>
                    </thead>
                    <tbody>


                        </tr>
                        <?php foreach ($model as $row): ?>
                            <tr>
                                <td><?= $row['id_evento'] ?></td>
                                <td><?= $row['nombre'] ?></td>
                                <td><?= $row['fecha'] ?></td>
                                <td><a href="<?= Url::toRoute(["evento/modificar", "id_evento" => $row['id_evento']]) ?>">Editar</a></td>
                                <td><a href="<?= Url::toRoute(["evento/eliminar", "id_evento" => $row['id_evento']]) ?>">Eliminar</a></td>
                                <td><a href="<?= Url::toRoute(["evento/verlista", "id_evento" => $row['id_evento'],'id_deporte'=>$row['id_deporte']]) ?>">convocados</a></td>
                            </tr>                           
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?=
    LinkPager::widget([
        "pagination" => $pages,
    ]);
    ?>
</article>

