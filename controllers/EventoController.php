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

class EventoController extends Controller {

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

    public function actionCrear() {

        $this->layout = 'mainadmin';
        $msg = null;
        $model = new ValidarEvento;

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
                $tabla->id_categoria = $model->id_categoria;
                $tabla->id_lista = $model->id_lista;
                if ($tabla->insert()) {
                    $msg = "Evento registrado con exito!";

                    $model->nombre = null;
                    $model->condicion = null;
                    $model->fecha = null;
                    $model->id_profesor_titular = null;
                    $model->id_profesor_suplente = null;
                    $model->id_deporte = null;
                    $model->id_categoria = null;
                    $model->id_lista = null;
                } else {
                    $msg = "No se pudo registrar Clase";
                }
            }
        }
        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre');
        $profesor = ArrayHelper::map(Profesor::find()->all(), 'dni', 'nombre');
        $categoria = ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre');
        $convocados = ArrayHelper::map(Convocados::find()->all(), 'id_lista', 'nombre');

        return $this->render("prubar_evento", ['model' => $model, 'msg' => $msg, "profesor" => $profesor,
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
                    $sql = "select id_evento, nombre,fecha "
                            . "from evento "
                            . "  where  "
                            . "fecha BETWEEN '$search_desde' AND '$search_hasta'  "
                            . "group by fecha";
                } else if ($search_desde == "") {
                    $sql = "select id_evento, nombre,fecha "
                            . "from evento "
                            . " where nombre like '%$search%'  ";
                } else {
                    $sql = "select id_evento, nombre,fecha "
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
                "pageSize" => 1,
                "totalCount" => $count->count(),
            ]);
            $sql = "select id_evento,nombre,fecha "
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
                $model->id_categoria = $tabla->id_categoria;
                $model->id_lista = $tabla->id_lista;
            }
        }
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (($model->load(Yii::$app->request->post()))) {
            if ($model->validate()) {
                $tabla = Evento::findOne($model->id_evento);
                $tabla->nombre = $model->nombre;
                $tabla->condicion = $model->condicion;
                $tabla->fecha = $model->fecha;
                $tabla->id_profesor_titular = $model->id_profesor_titular;
                $tabla->id_profesor_suplente = $model->id_profesor_suplente;
                $tabla->id_deporte = $model->id_deporte;
                $tabla->id_categoria = $model->id_categoria;
                $tabla->id_lista = $model->id_lista;


                if ($tabla->update()) {
                    $msg = "Evento registrado con exito!";

                    $model->nombre = null;
                    $model->condicion = null;
                    $model->fecha = null;
                    $model->id_profesor_titular = null;
                    $model->id_profesor_suplente = null;
                    $model->id_deporte = null;
                    $model->id_categoria = null;
                    $model->id_lista = null;
                    $this->redirect(["evento/buscar", 'msg' => $msg]);
                } else {
                    $msg = "no se pudo actualizar";
                }
            } else {
                $model->getErrors();
            }
        }


        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre');
        $profesor = ArrayHelper::map(Profesor::find()->all(), 'dni', 'nombre');
        $categoria = ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre');
        $convocados = ArrayHelper::map(Convocados::find()->all(), 'id_lista', 'nombre');

        return $this->render("probar_evento_modif", ['model' => $model, 'msg' => $msg, "profesor" => $profesor,
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

}
