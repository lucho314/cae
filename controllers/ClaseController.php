<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ValidarClase;
use app\models\Clase;
use yii\helpers\ArrayHelper;
use app\models\Prof_Comision;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\web\Session;
use app\models\Asistencia;
use app\models\Deportista_cat;

class ClaseController extends Controller {

    public $layout = 'mainadmin';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['crear', 'modificar', 'buscar'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserAdmin(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => ['crear'],
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

    public function actionCrear() {
        $this->layout = 'mainprofe';
        $msg = null;
        $model = new ValidarClase;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $session = new Session;
                $session->open();
                $session['fecha'] = $model->fecha;
                $session['comision'] = $model->comision;
                $session['observacion'] = $model->observaciones;
                $this->redirect(['clase/asistencia']);
            }
        }


        $dni = Yii::$app->user->identity->id;
        $comisiones = ArrayHelper::map
                        (
                        Prof_Comision::find()
                                ->where(['id_profesor_titular' => $dni])
                                ->orWhere(['id_profesor_titular' => $dni])
                                ->all(), 'id_comision', 'nombre_comision');


        return $this->render("nclase", ['model' => $model, 'msg' => $msg, 'comisiones' => $comisiones]);
    }

    public function actionAsistencia() {
        $session = new Session;
        if (!isset($session['comision'])) {
            $this->redirect(["clase/crear"]);
        }

        $msg = null;
        if (isset($_REQUEST['asistencia'])) {
            $si = [];
            $no = [];
            $fecha = $session['fecha'];
            $observacion = $session['observacion'];
            $id = $session['comision'];
            $datos = $_REQUEST['asistencia'];
            $n = count($datos);
            for ($i = 0; $i < $n; $i++) {
                $pos = strpos($datos[$i], "si");
                if ($pos !== false) {
                    $array = explode("-", $datos[$i], 2);
                    $si[] = $array[1];
                } else {
                    $array = explode("-", $datos[$i], 2);
                    $no[] = $array[1];
                }
            }
            $msg = $n;
            $sql1 = "insert into clase (fecha,observaciones,id_comision) value ('$fecha','$observacion','$id')";
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $connection->createCommand($sql1)->execute();
                $id = Yii::$app->db->getLastInsertID('clase');
                foreach ($si as $val) {
                    $nota=$_REQUEST[$val];
                    $connection->createCommand("insert into asistencia (id_clase,dni,asistencia,nota) value ('$id','$val',1,'$nota')")->execute();
                }
                foreach ($no as $val) {

                    $connection->createCommand("insert into asistencia (id_clase,dni,asistencia,nota) value ('$id','$val',0,10)")->execute();
                }
                $transaction->commit();
                $session->remove('fecha');
                $session->remove('comision');
                $session->remove('observaciones');
                $msg = "asistencia registrada con ecxito";
                $this->redirect(["clase/crear"]);
            } catch (\Exception $e) {
                //$msg = "problemas al registrar asistencia";
                throw $e;
            }
        }

        $comision = $session['comision'];
        $sql = "SELECT persona.nombre, persona.dni from persona,deportista_categoria,comision "
                . "WHERE comision.id_comision='$comision' and comision.id_categoria=deportista_categoria.id_categoria and "
                . "deportista_categoria.dni=persona.dni ";

        $alumnos = Yii::$app->db->createCommand($sql)->queryAll();
        $i = 1;
        $nota = null;
        $model = $alumnos;
        return $this->render("asistencia", ['model' => $model, 'msg' => $msg]);
    }

}
