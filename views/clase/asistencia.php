<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$nota = ['4' => 'Muy Bueno', '3' => 'Bueno', '2' => 'Regular', '1' => 'Malo'];
$this->title = 'SGD: Nueva Asistencia';
?>


<?=
Html::beginForm(
        Url::toRoute("clase/asistencia"), //action
        "post"//method
);
?>
<article class="col-xs-12 col-md-10">
    <h3>Asistencia:</h3>
    <?= $msg ?>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed">
            <thead Style="background-color:#4682B4; color:white;">
            <th>Nombre</th>
            <th>DNI</th>
            <th>Asistencia</th>
            <th>Desempeño</th>
            </thead>
            <?php
            $i = 0;
            foreach ($model as $row):
                ?>
                <tr>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['dni'] ?></td>
                    <td>     <fieldset>
                            
                                <input type="radio" name="asistencia[<?= $i ?>]" value="<?= "si-" . $row['dni'] ?>">si
                            
                            
                                <input type="radio" name="asistencia[<?= $i ?>]" value="<?= "no-" . $row['dni'] ?>" checked= checked   onchange$( "select#validardeportistamodif-categoria3" ).html( data ).prop("disabled", false)> no
                            
                        </fieldset>
                    </td>
                    <td>
                        <?= Html::dropDownList($row['dni'], $row['dni'], $nota, [ 'prompt' => 'Selecione desempeño', "disabled" => "disabled"]) ?>
                    </td>
                </tr>
                <?php
                $i++;
            endforeach;
            ?>
            </tbody>
        </table>
        <div class="botones">
            <input type="submit" value="Guardar" class="btn btn-primary">
        </div>
        <?= Html::endForm() ?>
    </div>
</article>