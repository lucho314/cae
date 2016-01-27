<?php

namespace app\controllers;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ValidarComision;
use app\models\Comision;
use app\models\Deporte;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Categoria;
use yii\helpers\ArrayHelper;
use app\models\ValidarBusqueda;
use yii\data\Pagination;
use yii\helpers\Html;
use app\models\Deportista;

class ComisionController extends Controller {

    public $layout = 'mainadmin';
    
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['crear','modificar','buscar','eliminar'],
                'rules' => [
                    [
                        'actions' => ['crear','modificar','buscar','eliminar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) 
                        {
                          return User::isUserAdmin(Yii::$app->user->identity->id);
                        }
                    ],
                            
                    [
                        'actions' => ['buscar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) 
                        {
                            return User::isUserProfe(Yii::$app->user->identity->id);
                        }

                    ],
                    [
                        'actions' => ['buscar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) 
                        {
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
        $msg = null;
        $model = new ValidarComision;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $tabla = new Comision;
                $tabla->dia = $model->dia;
                $tabla->nombre = $model->nombre;
                $tabla->hora_inicio = $model->hora_inicio;
                $tabla->hora_fin = $model->hora_fin;
                $tabla->id_categoria = $model->categoria;
                if ($tabla->insert()) {
                    $msg = "registros insertados correctamente";
                    $model->nombre_comision = null;
                    $model->hora_fin = null;
                    $model->hora_inicio = null;
                    $model->dia = null;
                } else {
                    $msg = "problemas al insertar";
                }
            }
        }
        $opciones = [];

        $parents = Deporte::find()->all();
        foreach ($parents as $id => $p) {
            $children = Categoria::find()->where("id_deporte=:id", [":id" => $p->id_deporte])->all();
            $child_options = [];
            foreach ($children as $child) {
                $child_options[$child->id_categoria] = $p->nombre . "-" . $child->nombre_categoria;
            }
            $opciones[$p->nombre] = $child_options;
        }


        return $this->render("ncomision", ["msg" => $msg, "model" => $model, 'opciones' => $opciones]);
    }

    public function actionModificar() {
        $msg = null;
        $model = new ValidarComision();

        if (Yii::$app->request->get()) {
            $id = Html::encode($_GET["id_comision"]);
            if ((int) $id) {
                $table = Deportista::find();
                $model->id_comision = $_REQUEST['id_comision'];
                $table = Comision::findOne($model->id_comision);
                $model->nombre_comision = $table->nombre_comision;
                $model->dia = $table->dia;
                $model->categoria = $table->id_categoria;
                $model->hora_fin = $table->hora_fin;
                $model->hora_inicio = $table->hora_inicio;
            }
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Comision::findOne($model->id_comision);
                $table->nombre_comision = $model->nombre_comision;
                $table->id_categoria = $model->categoria;
                $table->hora_fin = $model->hora_fin;
                $table->hora_inicio = $model->hora_inicio;

                if ($table->update()) {
                    $msg = "modificado con exito";
                    $model->nombre_comision = null;
                    $model->categoria = null;
                    $model->hora_fin = null;
                    $model->hora_inicio = null;
                    $this->redirect(["comision/buscar", 'msg' => $msg]);
                }
            }
        }
        $opciones = ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre_categoria');
        return $this->render("modificar_comision", ['msg' => $msg, 'model' => $model, 'opciones' => $opciones]);
    }

    public function actionBuscar($msg = null) {

        $form = new ValidarBusqueda;
        $search = null;
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                $search = Html::encode($form->q);

                $sql = "select DISTINCT id_comision, nombre_comision,dia,nombre_categoria,hora_inicio,hora_fin "
                        . "from comision, categoria "
                        . "where comision.id_categoria=categoria.id_categoria and "
                        . "nombre_comision like '%$search%' or nombre_categoria like '%$search%' "
                        . "or dia like '%$search%' or hora_inicio like '%$search%' or hora_fin like '%$search%'"
                        . "group by comision.id_comision";
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
            $table = Comision::find();

            $count = clone $table;
            $pages = new Pagination([
                "pageSize" => 10,
                "totalCount" => $count->count(),
            ]);
            $sql = "select id_comision, nombre_comision,dia,nombre_categoria,hora_inicio,hora_fin "
                    . "from comision, categoria "
                    . "where comision.id_categoria=categoria.id_categoria "
                    . "LIMIT $pages->limit OFFSET $pages->offset";
            $command = Yii::$app->db->createCommand($sql)->queryAll();
            $model = $command;
        }
        return $this->render("buscar", ['model' => $model, 'msg' => $msg, "pages" => $pages, "model" => $model, "form" => $form, "search" => $search]);
    }

    public function actionEliminar() {
        $msg = null;
        if (isset($_REQUEST['id_comision'])) {
            $tabla = Comision::findOne($_REQUEST['id_comision']);
            if (Comision::find()->where(['id_categoria' => $tabla->id_categoria])->count() == 0) {
                if ($tabla->delete()) {
                    $msg = "se elimino correctamente";
                } else {
                    $msg = "problemas al eliminar";
                    
                }
            }
            $id=$tabla->id_comision;
            $confirmar="¿Esta seguro de dejar a la categoria sin ninguna comision?";
            return $this->render("confirmar",['msg'=>$confirmar,'id'=>$id]);
        }
        if(isset($_REQUEST['id']))
        {
            $tabla = Comision::findOne($_REQUEST['id']);
             if ($tabla->delete()) {
                    $msg = "se elimino correctamente";
                } else {
                    $msg = "problemas al eliminar";
                    
                }
            
        }
        $this->redirect(["comision/buscar", 'msg' => $msg]);
    }

}
