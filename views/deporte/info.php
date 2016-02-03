<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<article>
    <h3>Nombre del deporte:  <?= $datos['nombre_deporte'] ?> </h3>
    <h3> Cantidad de Profesores:  <?= $datos['cantidad_profesor'] ?>       <a href="<?= Url::toRoute(["infoprofesores",'id'=>$datos['id_deporte']])?>">detalle</a></h3>
    <h3> Cantidad de Categoria:  <?= $datos['cantidad_categoria'] ?>       <a href="<?= Url::toRoute(["infocategoria",'id'=>$datos['id_deporte']])?>">detalle</a></h3>
    <h3> Cantidad de sub comision:  <?= $datos['cantidad_subcomision'] ?>   <a href="<?= Url::toRoute(["infosubcomision",'id'=>$datos['id_deporte']])?>">detalle</a></h3>
    <h3> Cantidad de deportistas:  <?= $datos['cantidad_deportista'] ?>     <a href="<?= Url::toRoute(["infodeportista",'id'=>$datos['id_deporte']])?>">detalle</a></h3>

</article>