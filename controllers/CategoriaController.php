<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ValidarCategoria;
use app\models\Categoria;
use yii\helpers\ArrayHelper;
use app\models\Deporte;
use app\models\Profesor;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Comision;
use app\models\Evento;
use app\models\Deportista_cat;

class CategoriaController extends Controller {

    public $layout = 'mainadmin';

    public function actionCrear() {
        $msg = null;
        $model = new ValidarCategoria;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $tabla = new Categoria;
                $tabla->nombre_categoria = strtolower($model->nombre_categoria);
                $tabla->edad_minima = $model->edad_min;
                $tabla->edad_maxima = $model->edad_max;
                $tabla->id_deporte = $model->deporte;
                $tabla->id_profesor_titular = $model->profesor_titular;
                $tabla->id_profesor_suplente = $model->profesor_suplente;
                if ($tabla->insert()) {
                    $msg = "Categoria registrada con exito!";
                    $model->nombre_categoria = null;
                    $model->edad_max = null;
                    $model->edad_min = null;
                } else {
                    $msg = "No se pudo registrar Categoria.";
                }
            }
        }
        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre');
        $profesor = ArrayHelper::map(Profesor::find()->all(), 'dni', 'nombre');
        return $this->render("ncategoria", ["msg" => $msg, "model" => $model, "deporte" => $deporte, "profesor" => $profesor]);
    }

    public function actionModificar() {
        $msg = null;
        $model = new ValidarCategoria;

        if (isset($_REQUEST['id_categoria'])) {
            $model->id_categoria = $_REQUEST['id_categoria'];
            $table = Categoria::findOne($model->id_categoria);
            $model->nombre = $table->nombre;
            $model->edad_min = $table->edad_minima;
            $model->edad_max = $table->edad_maxima;
            $model->deporte = $table->id_deporte;
            $model->profesor_suplente = $table->id_profesor_suplente;
            $model->profesor_titular = $table->id_profesor_titular;
        }


        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Categoria::findOne($model->id_categoria);
                $table->nombre = $model->nombre;
                $table->edad_maxima = $model->edad_max;
                $table->edad_minima = $model->edad_min;
                $table->id_deporte = $model->deporte;
                $table->id_profesor_suplente = $model->profesor_suplente;
                $table->id_profesor_titular = $model->profesor_titular;

                if ($table->update()) {
                    $msg = "modificado con exito";
                    $model->nombre = NULL;
                    $model->edad_min = NULL;
                    $model->edad_max = NULL;
                    $model->deporte = NULL;
                    $model->profesor_suplente = NULL;
                    $model->profesor_titular = NULL;
                    $this->redirect(["categoria/buscar", 'msg' => $msg]);
                }
            }
        }

        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre');
        $profesor = ArrayHelper::map(Profesor::find()->all(), 'dni', 'nombre');
        return $this->render("modificar_categoria", ['msg' => $msg, 'model' => $model, 'profesor' => $profesor, 'deporte' => $deporte]);
    }

    public function actionBuscar($msg = null) {
        $parents = Deporte::find()->all();
        foreach ($parents as $id => $p) {
            $children = Categoria::find()->where("id_deporte=:id", [":id" => $p->id_deporte])->all();
            $child_options = [];
            foreach ($children as $child) {
                $child_options[$child->id_categoria] = $p->nombre_categoria . "-" . $child->nombre;
            }
            $opciones[$p->nombre] = $child_options;
        }
        return $this->render('buscar', ['opciones' => $opciones, 'msg' => $msg]);
    }

    public function actionEliminar() {
        $msg = null;
        $id;
        if (!isset($_REQUEST['confirmar']) && isset($_REQUEST['id'])) {
            $id=$_REQUEST['id'];
            $tabla = Evento::find()->where(['id_categoria' => $id]);
            if ($tabla->count() != 0) {
                $msg = "existen eventos registrados con esta categoria";
            }
            $tabla = Deportista_cat::find()->where(['id_categoria' => $id]);
            if ($tabla->count() != 0) {
                $msg.="<br>" . "existen Deportistas registrados con esta categoria";
            }
            $tabla = Comision::find()->where(['id_categoria' => $id]);
            if ($tabla->count() != 0) {
                $msg.="<br>" . "existen Comisiones registrados con esta categoria";
            }
            if ($msg != null) {
                return $this->render("confirmar", ['msg' => $msg,'id'=>$id]);
            } 
            else {
                if (Categoria::deleteAll($id)) {
                    $msg = "Categoria eliminado con exito";
                } else {
                    $msg = "ocurrio un error";
                }
            }
        } else if (isset($_REQUEST['confirmar'])) {
            if (Categoria::deleteAll($_REQUEST['id'])) {
                $msg = "Categoria eliminado con exito";
            } else {
                $msg = "ocurrio un error";
            }
        }

        return $this->render("eliminar", ['msg' => $msg]);
    }

}
