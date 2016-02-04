<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<article class="col-xs-12 col-md-10">
    <div class="row">
        <div class="col-xs-12">
            <h3>Lista de Comisiones</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead Style="background-color:#4682B4; color:white;">
                    <th>Nombre</th>
                    <th>Dni</th>
                    <th>Categoria</th>
                    <th>Convocar</th>
                    </thead>
                    <tbody>
                        <?php foreach ($model as $valor): ?>
                            <tr>
                                <td> <?= $valor['nombre'] ?></td>
                                <td><?= $valor['dni'] ?> </td>
                                <td><?= $valor['nombre_categoria'] ?> </td>
                              <td> <input type="button" value="Eliminar" onclick="Eliminar(this.parentNode.parentNode.rowIndex,<?= $valor['dni'] ?>)"/></td>
                            </tr>   
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?= Url::toRoute(["evento/modif_agregar"]) ?>" class="btn btn-default">Agregar</a>
                <a href="<?= Url::toRoute(["evento/modificarlista","sacar"=>'si']) ?>" class="btn btn-default">confirmar</a>
            </div>

        </div>
    </div>
</article>

<script type="text/javascript">
    function Eliminar(i, dni) {


        document.getElementsByTagName("table")[0].setAttribute("id", "tableid");
        document.getElementById("tableid").deleteRow(i);
        var dataString = 'id=' + dni;
        $.ajax({
            type: "POST",
            url: "<?= Url::toRoute(["evento/quitar"]) ?>",
            data: dataString
        });
    }
</script>
