<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Categoria;
use app\models\Deporte;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<head>
     <script type="text/javascript" src="../web/js/jquery.js"></script>
     
</head>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'deporte')->dropDownList(
                                $deporte,
                                [
                                    'prompt'=>'Seleccione Deporte',
                                    'onchange'=>'
                                        $.post( "index.php?r=site/opcion&id='.'"+$(this).val(), function( data ) {
                                            $( "select#validarcategoria-categoria" ).html( data );
                                        });'
                                ]); ?>

	<?= $form->field($model,'categoria')->dropDownList(
                                [],
                                [
                                    'actived'=>false,
                                    'prompt'=>'Selecione Categoria',
                                ]); ?>

