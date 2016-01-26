<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
?>
<article class="col-xs-12 col-md-10"> 
<?=$msg?>
<?php $f = ActiveForm::begin([
    "method" => "post",
    "action" => Url::toRoute("comision/buscar"),
    "enableClientValidation" => true,
]);
?>
<div class="form-group">
    <label>Buscar:</label>
    <?= $f->field($form, "q")->input("search")->label(false)?>
</div>
 
<?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
 
<?php $f->end() ?>

    <h3>Lista de alumnos</h3>
    <table class="table table-bordered">
        <tr>
            <th>Nombre</th>
            <th>Dia</th>
            <th>Hora inicio</th>
            <th>Hora fin</th>
            <th>Categoria</th>
            <th>Modificar?</th>
            <th>Eliminar?</th>
        </tr>
<?php foreach ($model as $row): ?>
            <tr>
                <td><?= $row['nombre_comision'] ?></td>
                <td><?= $row['dia']?></td>
                <td><?= $row['hora_inicio'] ?></td>
                <td><?= $row['hora_fin'] ?></td>
                <td><?= $row['nombre_categoria'] ?></td>
                <td><a href="<?= Url::toRoute(["comision/modificar", "id_comision" => $row['id_comision']]) ?>">Editar</a></td>
                <td>
                     <a href="#" data-toggle="modal" data-target="#id_comision<?= $row['id_comision'] ?>">Eliminar</a>
            <div class="modal fade" role="dialog" aria-hidden="true" id="id_comision<?= $row["id_comision"] ?>">
                      <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Eliminar comision</h4>
                              </div>
                              <div class="modal-body">
                                    <p>¿Realmente deseas eliminar la comision<?= $row["nombre_comision"] ?>?</p>
                              </div>
                              <div class="modal-footer">
                              <?= Html::beginForm(Url::toRoute("comision/eliminar"), "POST") ?>
                                    <input type="hidden" name="id_comision" value="<?= $row['id_comision'] ?>">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                              <?= Html::endForm() ?>
                              </div>
                            </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </td>
                    
                </td>
            </tr>
<?php endforeach ?>
    </table>
    
  <?= LinkPager::widget([
    "pagination" => $pages,
]);?>
    </body>
</article>

