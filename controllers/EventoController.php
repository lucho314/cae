<?php

namespace app\controllers;

use app\models\user;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Deportista;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\helpers\Html;
use app\models\Persona;
use app\models\ValidarEvento;
use app\models\Evento;
use app\models\Deporte;
use app\models\Profesor;
use app\models\Categoria;
use app\models\Convocados;
use app\models\ValidarBusqueda;
use app\models\Vdep_Cat;

class EventoController extends Controller {

    public $layout;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['crear,modificar,eliminar,buscar'],
                'rules' => [
                    [
                        'actions' => ['crear,modificar,eliminar,buscar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserAdmin(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => ['crear,modificar,eliminar,buscar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserProfe(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => ['buscar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserSubcomision(Yii::$app->user->identity->id);
                }
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionCrear($msg = null) {

        $this->layout = 'mainadmin';
        $msg = null;
        $model = new ValidarEvento;
        session_start();
        if (isset($_SESSION['dni'])) {
            unset($_SESSION['dni']);
        }
        if (isset($_SESSION['deporte'])) {
            unset($_SESSION['deporte']);
        }
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {
                $tabla = new Evento;
                $tabla->nombre = $model->nombre;
                $tabla->condicion = $model->condicion;
                $tabla->fecha = $model->fecha;
                $tabla->id_profesor_titular = $model->id_profesor_titular;
                $tabla->id_profesor_suplente = $model->id_profesor_suplente;
                $tabla->id_deporte = $model->id_deporte;
                if ($tabla->insert()) {
                    $model->nombre = null;
                    $model->condicion = null;
                    $model->fecha = null;
                    $model->id_profesor_titular = null;
                    $model->id_profesor_suplente = null;
                    if ($model->convocados == 1) {
                        $_SESSION['deporte'] = $model->id_deporte;
                        $_SESSION['id_evento'] = Yii::$app->db->getLastInsertID('evento');
                        $model->id_deporte = null;
                        $this->redirect(["evento/clista"]);
                    }
                    $model->id_deporte = null;
                } else {
                    $msg = "No se pudo registrar Clase";
                }
            }
        }
        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre_deporte');
        $profesor = ArrayHelper::map(Profesor::find()->all(), 'dni', 'nombre');
        $categoria = ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre_categoria');
        $convocados = ArrayHelper::map(Convocados::find()->all(), 'id_lista', 'nombre');

        return $this->render("crear", ['model' => $model, 'msg' => $msg, "profesor" => $profesor,
                    "categoria" => $categoria, "deporte" => $deporte, "convocados" => $convocados,
        ]);
    }

    public function actionBuscar($msg = null) {

        $form = new ValidarBusqueda;
        $search = null;
        $search_desde = null;
        $search_hasta = null;
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                $search = Html::encode($form->q);
                $search_desde = Html::encode($form->desde);
                $search_hasta = Html::encode($form->hasta);
                if ($search == "") {
                    $sql = " id_deporte,id_evento, nombre,fecha "
                            . "from evento "
                            . "  where  "
                            . "fecha BETWEEN '$search_desde' AND '$search_hasta'  "
                            . "group by fecha";
                } else if ($search_desde == "") {
                    $sql = "select id_deporte,id_evento, nombre,fecha "
                            . "from evento "
                            . " where nombre like '%$search%'  ";
                } else {
                    $sql = "select id_deporte, id_evento, nombre,fecha "
                            . "from evento "
                            . "  where nombre like '%$search%' and "
                            . "fecha BETWEEN '$search_desde' AND '$search_hasta'  "
                            . "group by fecha";
                }

                $command = Yii::$app->db->createCommand($sql)->query();
                $count = clone $command;
                $pages = new Pagination([
                    "pageSize" => 10,
                    "totalCount" => $count->count()
                ]);
                $sql.=" LIMIT $pages->limit OFFSET $pages->offset ";
                $command = Yii::$app->db->createCommand($sql)->queryAll();
                $model = $command;
            } else {
                $form->getErrors();
            }
        } else {
            $table = Evento::find();

            $count = clone $table;
            $pages = new Pagination([
                "pageSize" => 10,
                "totalCount" => $count->count(),
            ]);
            $sql = "select id_deporte, id_evento,nombre,fecha "
                    . "from evento"
                    . " "
                    . "LIMIT $pages->limit OFFSET $pages->offset";
            $command = Yii::$app->db->createCommand($sql)->queryAll();
            $model = $command;
        }
        return $this->render("buscar", ['model' => $model, 'msg' => $msg, "pages" => $pages, "model" => $model, "form" => $form, "search" => $search]);
    }

    public function actionModificar() {
        $this->layout = "mainadmin";
        $model = new ValidarEvento;
        $msg = null;

        if (isset($_REQUEST['id_evento'])) {
            if ($_REQUEST['id_evento']) {
                $model->id_evento = $_REQUEST['id_evento'];
                $tabla = Evento::findOne($model->id_evento);
                $model->nombre = $tabla->nombre;
                $model->condicion = $tabla->condicion;
                $model->fecha = $tabla->fecha;
                $model->id_profesor_titular = $tabla->id_profesor_titular;
                $model->id_profesor_suplente = $tabla->id_profesor_suplente;
                $model->id_deporte = $tabla->id_deporte;
                $model->convocados = 1;
            }
        }
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (($model->load(Yii::$app->request->post()))) {
            $msg = "askdjla";
            if ($model->validate()) {

                $tabla = Evento::findOne($model->id_evento);
                $tabla->nombre = $model->nombre;
                $tabla->condicion = $model->condicion;
                $tabla->fecha = $model->fecha;
                $tabla->id_profesor_titular = $model->id_profesor_titular;
                $tabla->id_profesor_suplente = $model->id_profesor_suplente;
                $tabla->id_deporte = $model->id_deporte;



                if ($tabla->update()) {
                    $msg = "Evento registrado con exito!";

                    $model->nombre = null;
                    $model->condicion = null;
                    $model->fecha = null;
                    $model->id_profesor_titular = null;
                    $model->id_profesor_suplente = null;
                    $model->id_deporte = null;
                    $this->redirect(["evento/buscar", 'msg' => $msg]);
                } else {
                    $msg = "no se pudo actualizar";
                }
            } else {
                $model->getErrors();
            }
        }


        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre_deporte');
        $profesor = ArrayHelper::map(Profesor::find()->all(), 'dni', 'nombre');
        $categoria = ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre_categoria');
        $convocados = ArrayHelper::map(Convocados::find()->all(), 'id_lista', 'nombre');

        return $this->render("modificar", ['model' => $model, 'msg' => $msg, "profesor" => $profesor,
                    "categoria" => $categoria, "deporte" => $deporte, "convocados" => $convocados,
        ]);
    }

    public function actionEliminar() {

        $msg = null;
        if ((int) isset($_REQUEST["id_evento"])) {
            $tabla = Evento::findOne($_REQUEST['id_evento']);
            if ($tabla->delete()) {
                $msg = "Eliminacion realizada con exito!";
                $this->redirect(["evento/buscar", 'msg' => $msg]);
            } else {
                $msg = "No se pudo realizar la eliminacion";
            }
        }

        return $this->render("buscar", ["msg" => $msg]);
    }

    public function actionClista() {
        $this->layout = "mainprofe";
        $msg = null;
        session_start();
        $model = array();
        if (isset($_SESSION['deporte'])) {
            $deporte = $_SESSION['deporte'];
        } else {
            $this->redirect(['evento/crear']);
        }
        $sql = "select nombre, dni,nombre_categoria from vdep_cat where id_deporte=$deporte";
        if (!isset($_SESSION['dni'])) {
            $model = Yii::$app->db->createCommand($sql)->queryAll();
        } else {

            $datos = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($datos as $val) {

                if (!in_array($val['dni'], $_SESSION['dni'])) {
                    $model[] = array('nombre' => $val['nombre'], 'dni' => $val['dni'], 'nombre_categoria' => $val['nombre_categoria']);
                }
            }
        }
        return $this->render('clista', ['model' => $model, 'msg' => $msg]);
    }

    public function actionAgregar() {
        session_start();
        $_SESSION['dni'][] = $_POST['id'];
        $hola = $_POST['id'];
    }

    public function actionQuitar() {
        session_start();
        if (is_numeric($_POST['id'])) {
            $aux[] = $_POST['id'];
            $array = array_diff($_SESSION['dni'], $aux);
            unset($_SESSION['dni']);
            foreach ($array as $val) {
                $_SESSION['dni'][] = $val;
            }
        }
    }

    public function actionConflista() {
        $this->layout = "mainprofe";
        session_start();
        if (isset($_REQUEST['id'])) {
            if ($_REQUEST['id'] == 'confirmar') {
                $tabla = Vdep_Cat::find()->where(['IN', 'dni', $_SESSION['dni']])->andWhere(['id_deporte' => $_SESSION['deporte']])->all();
                $convocados = new Convocados;
                $id_evento = $_SESSION['id_evento'];
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                foreach ($tabla as $valor) {
                    $dni = $valor['dni'];
                    $nombre = $valor['nombre'];
                    $connection->createCommand("insert into convocados (dni,nombre,id_evento) VALUES ('$dni','$nombre','$id_evento')")->execute();
                }
                $transaction->commit();
                unset($_SESSION['dni']);
                unset($_SESSION['id_evento']);
                unset($_SESSION['deporte']);
                $this->redirect(['evento/crear']);
            }
        } else {
            $model = \app\models\Vdep_Cat::find()->where(['id_deporte' => $_SESSION['deporte']])->andWhere(['IN', 'dni', $_SESSION['dni']])->all();
            return $this->render('confirmar', ['model' => $model]);
        }
    }

    public function actionVerlista($id_evento, $id_deporte) {
        $table = Convocados::find()->where(["id_evento" => $id_evento])->all();
        session_start();
        $dni = array();

        foreach ($table as $val) {
            $dni[] = $val['dni'];
        }
        $table = Vdep_Cat::find()->where(['IN', 'dni', $dni])->andWhere(['id_deporte' => $id_deporte])->all();
        foreach ($table as $dni) {
            $_SESSION['dni'][] = $dni['dni'];
        }

        $_SESSION['id_deporte'] = $id_deporte;
        $_SESSION['id_evento'] = $id_evento;
        return $this->render("lista_convocados", ['model' => $table]);
    }

    public function actionModif_agregar() {
        session_start();
        $deporte = $_SESSION['id_deporte'];
        $sql = "select nombre, dni,nombre_categoria from vdep_cat where id_deporte=$deporte";

        if (!isset($_SESSION['dni'])) {
            $model = Yii::$app->db->createCommand($sql)->queryAll();
        } else {

            $datos = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($datos as $val) {

                if (!in_array($val['dni'], $_SESSION['dni'])) {
                    $model[] = array('nombre' => $val['nombre'], 'dni' => $val['dni'], 'nombre_categoria' => $val['nombre_categoria']);
                }
            }
        }
        return $this->render("m_agregar", ["model" => $model]);
    }

    public function actionModificarlista($sacar = null) {
        $this->layout = "mainprofe";
        session_start();
        $msg = null;
        $id_evento = $_SESSION['id_evento'];
        $connection = \Yii::$app->db;
        if ($sacar == "si") {
            $msg = "reconocio string";

            $msg = "reconocio si";
            $table = Convocados::find()->where(['id_evento' => $_SESSION['id_evento']])->all();
            foreach ($table as $val) {
                $dni[] = $val['dni'];
            }
            if (isset($_SESSION['dni'])) {
                $datos = array_diff($dni, $_SESSION['dni']);
                $cant = count($datos);
                for ($i = 0; $i < $cant; $i++) {
                    $id = $datos[$i];
                    $connection->createCommand("delete from convocados where dni=$id")->execute();
                }
            }
            else
            {
                $connection->createCommand("delete from convocados where id_evento=$id_evento")->execute();
            }
        } else {
            $tabla = Vdep_Cat::find()->where(['IN', 'dni', $_SESSION['dni']])->andWhere(['id_deporte' => $_SESSION['id_deporte']])->all();
            foreach ($tabla as $valor) {
                $dni = $valor['dni'];
                $nombre = $valor['nombre'];
                $connection->createCommand("insert IGNORE into convocados (dni,nombre,id_evento) VALUES ('$dni','$nombre','$id_evento')")->execute();
            }
        }
        unset($_SESSION['dni']);
        unset($_SESSION['id_evento']);
        unset($_SESSION['deporte']);
        $this->redirect(['evento/buscar', 'msg' => $msg]);
    }

}
