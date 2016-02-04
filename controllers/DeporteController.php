<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ValidarDeporte;
use app\models\Deporte;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\data\Pagination;
use yii\helpers\Html;
use app\models\ValidarBusqueda;
use yii\helpers\ArrayHelper;

class DeporteController extends Controller {

    public $layout = 'mainadmin';

    public function actionCrear() {

        $msg = null;
        $model = new ValidarDeporte;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $tabla = new Deporte;
                $tabla->nombre_deporte = strtolower($model->nombre_deporte);
                if ($tabla->insert()) {
                    $msg = "Depote registrado con exito";
                    $model->nombre = null;
                } else {
                    $msg = "problemas al cargar en base";
                }
            } else {

                $model->getErrors();
            }
        }

        return $this->render("nuevo_deporte", ['model' => $model, 'msg' => $msg]);
    }

    public function actionBuscar($msg = null) {

        $form = new ValidarBusqueda;
        $search = null;
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                $search = Html::encode($form->q);

                $sql = "select id_deporte,nombre_deporte from deporte "
                        . "where id_deporte like '%$search%' or nombre_deporte like '%$search%'";
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
            $table = Deporte::find();

            $count = clone $table;
            $pages = new Pagination([
                "pageSize" => 10,
                "totalCount" => $count->count(),
            ]);
            $sql = "select id_deporte,nombre_deporte from deporte "
                    . "LIMIT $pages->limit OFFSET $pages->offset";
            $command = Yii::$app->db->createCommand($sql)->queryAll();
            $model = $command;
        }
        return $this->render("buscar", ['msg' => $msg, "pages" => $pages, "model" => $model, "form" => $form, "search" => $search]);
    }

    public function actionModificar() {
        $msg = null;
        $model = new ValidarDeporte;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->get()) {

            $model->deporte = Html::encode($_GET['id_deporte']);
            $model->nombre_deporte = Html::encode($_GET['nombre_deporte']);

            if ((int) $model->deporte) {
                return $this->render("modificar_deporte", ['model' => $model,
                            'deporte' => $model->deporte,
                            'nombre_deporte' => $model->nombre_deporte]);
            }
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Deporte::findOne($model->deporte);
                $table->id_deporte = $model->deporte;
                $table->nombre_deporte = $model->nombre_deporte;

                if ($table->update()) {
                    $model->nombre_deporte = null;
                    $model->deporte = null;
                }
            } else {
                $model->getErrors();
            }
        }

        $this->redirect(["deporte/buscar"]);
    }

    public function actionEliminar() {
        $msg = null;
        if ((int) isset($_REQUEST["deporte"])) {
            $tabla = Deporte::findOne($_REQUEST['deporte']);
            if ($tabla->delete()) {
                $msg = "Eliminacion realizada con exito!";
            } else {
                $msg = "No se pudo realizar la eliminacion";
            }
        }
        $this->redirect(["deporte/buscar", 'msg' => $msg]);
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['crear', 'modificar', 'eliminar', 'buscar', 'infodeporte'],
                'rules' => [
                    [
                        'actions' => ['crear', 'modificar', 'eliminar', 'buscar', 'infodeporte'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserAdmin(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserProfe(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => [],
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

    public function actionInfodeporte($id=null) {
        if(!preg_match("/^[0-9]+$/", $id))
        {
            $this->redirect(["deporte/buscar"]);
        }
        $sql = "select id_deporte ,cantidad_deportista,cantidad_categoria,cantidad_subcomision, "
                . "cantidad_profesor,nombre_deporte from vinfo_deporte where id_deporte=$id";

        $datos = Yii::$app->db->createCommand($sql)->queryOne();

        return $this->render("info", ['datos' => $datos]);
    }

    public function actionInfoprofesores($id) {
         if(!preg_match("/^[0-9]+$/", $id))
        {
            $this->redirect(["deporte/buscar"]);
        }
        $sql = "select persona.dni,nombre,apellido,domicilio,email from "
                . "persona,profesor,profesor_deporte where profesor_deporte.id_deporte=$id and persona.dni=profesor.dni";
        $datos = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render("infoprofesor", ['datos' => $datos]);
    }

    public function actionInfocategoria($id) {
         if(!preg_match("/^[0-9]+$/", $id))
        {
            $this->redirect(["deporte/buscar"]);
        }
        $sql = "SELECT categoria.id_deporte,categoria.id_categoria,categoria.nombre_categoria,categoria.edad_minima,categoria.edad_maxima,sub1.prof_titular, sub2.prof_suplente FROM categoria
                    INNER JOIN 
                    (SELECT concat(persona.nombre, ', ' , persona.apellido) AS 'prof_titular',categoria.id_categoria FROM persona INNER JOIN categoria ON persona.dni=categoria.id_profesor_titular)AS sub1
                    ON categoria.id_categoria=sub1.id_categoria
                    INNER JOIN
                    (SELECT concat(persona.nombre, ', ' , persona.apellido) AS 'prof_suplente',categoria.id_categoria FROM persona RIGHT JOIN categoria ON
                    persona.dni=categoria.id_profesor_suplente)AS sub2
                    ON categoria.id_categoria=sub2.id_categoria  where categoria.id_deporte=$id";
        $datos = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render("infocategoria", ['datos' => $datos]);
    }
    
    public function actionInfodeportista($id) {
         if(!preg_match("/^[0-9]+$/", $id))
        {
            $this->redirect(["deporte/buscar"]);
        }
        $sql = "select persona.dni,nombre,apellido,domicilio,email from "
                . "persona,deportista,deportista_categoria  where deportista_categoria.id_deporte=$id and persona.dni=deportista.dni and deportista_categoria.dni=deportista.dni";
        $datos = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render("infodeportista", ['datos' => $datos]);
    }

}
