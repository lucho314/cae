<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <script type="text/javascript" src="../web/js/jquery.js"></script>
        <script type="text/javascript" src="../web/js/menu.js"></script>
        <link href='../web/imagenes/cae.ico' rel='shortcut icon' type='image/x-icon'>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <script type="text/javascript">
            $(document).ready(main);

            var contador = 1;

            function main() {
                $('.menu_movil').click(function () {
                    // $('nav').toggle(); Forma Sencilla de aparecer y desaparecer

                    if (contador == 1) {
                        $('aside').animate({
                            left: '0'
                        });
                        contador = 0;
                    } else {
                        contador = 1;
                        $('aside').animate({
                            left: '-100%'
                        });
                    }
                    ;

                });

                // Mostramos y ocultamos submenus
                $('.submenu').click(function () {
                    $(this).children('.children').slideToggle();
                })
            }
            ;
        </script>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="container-fluid">
            <div class="row">
                <header class="col-xs-12 cabecera">
                    <img src="../web/imagenes/cae.png" class="logo">
                    <h1>CAE: Club Atlético Estudiante</h1>
                </header>
                <header class="col-xs-12 menu_movil">
                    <div class="row">
                        <a href="javascript:void(0);" class="container-fluid bt-menu">Menu<span class="glyphicon glyphicon-tasks"></span></a>
                    </div>
                </header>
                <aside class="col-xs-12 col-md-2 menu">
                    <ul class="container-fluid">
                        <li class="row"><a href="<?=Url::toRoute("clase/crear")?>"><span class="glyphicon glyphicon-list-alt"></span>Asistencia</a></li>
                        <li class="row submenu"><a href="javascript:void(0);"><span class="glyphicon glyphicon-calendar"></span>Eventos<span class="glyphicon glyphicon-menu-down"></span></a>
                            <ul class="children">
                                <li><a href="<?=Url::toRoute("evento/crear")?>"><span class="glyphicon glyphicon-plus"></span>Alta</a></li>
                                <li><a href=""><span class="glyphicon glyphicon-ok"></span>Modificar</a></li>
                                <li><a href=""><span class="glyphicon glyphicon-remove"></span>Eliminar</a></li>
                            </ul>
                        </li>
                        <li class="row"><a href=""><span class="glyphicon glyphicon-align-left"></span>Estadisticas</a></li>
                        <li class="row"><a href=""><span class="glyphicon glyphicon-search"></span>Busqueda</a></li>
                        <li class="row"><a href="<?=Url::toRoute("usuario/logout")?>" data-method="post"><span class="glyphicon glyphicon-off"></span>Cerrar Sesion</a></li>
                    </ul>		
                </aside>
                <?= $content ?>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
