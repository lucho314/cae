<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ValidarDeportista;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Deportista;
use yii\helpers\ArrayHelper;
use app\models\ValidarBusqueda;
use yii\data\Pagination;
use yii\helpers\Html;
use app\models\Persona;
use app\models\Planilla;
use app\models\ValidarDeportistamodif;
use app\models\Deporte;
use app\models\Categoria;
use app\models\User;
use yii\web\UploadedFile;

class DeportistaController extends Controller {

    public $layout = 'mainadmin';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['crear', 'modificar', 'buscar'],
                'rules' => [
                    [
                        'actions' => ['crear', 'modificar', 'buscar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserAdmin(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => ['buscar'],
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
        $model = new ValidarDeportista;
        $msg = NULL;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstances($model, 'file');
            if ($model->validate()) {
                foreach ($model->file as $file) {
                    $file->name = $model->dni;
                    $file->saveAs('archivos/' . $file->baseName);
                }
                $categorias = ['1' => $model->categoria1, '2' => $model->categoria2, '3' => $model->categoria3];
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $sql1 = "insert into persona (dni,nombre,apellido,domicilio,telefono,email) value ('$model->dni','$model->nombre','$model->apellido','$model->domicilio','$model->telefono','$model->email')";
                $sql2 = "insert into planilla (medico_cabecera,grupo_sanguineo,obra_social,medicamento,desc_medicamento,alergia,
                 desc_alergia,anemia,enf_cardiologica,desc_cardiologia,asma,desc_asma,presion,convulsiones,ultima_convulsion,trastornos_hemorragicos,
                 fuma,cuanto_fuma,diabetes,desc_diabetes,tratamiento,desc_tratamiento,internaciones,desc_internacion,nombreyapellido1,domicilio1,telefono1,
                 nombreyapellido2,domicilio2,telefono2,observaciones) value ('$model->medico_cabecera','$model->grupo_sanguineo','$model->obra_social','$model->medicamento','$model->desc_medicamento','$model->alergia',
                 '$model->desc_alergia','$model->anemia','$model->enf_cardiologica','$model->desc_cardiologia','$model->asma','$model->desc_asma','$model->presion','$model->convulsiones','$model->ultima_convulsion','$model->trastornos_hemorragicos',
                 '$model->fuma','$model->cuanto_fuma','$model->diabetes','$model->desc_diabetes','$model->tratamiento','$model->desc_tratamiento','$model->internaciones','$model->desc_internacion','$model->nombreyapellido1','$model->domicilio1','$model->telefono1',
                 '$model->nombreyapellido2','$model->domicilio2','$model->telefono2','$model->observaciones')";



                try {
                    $connection->createCommand($sql1)->execute();
                    $connection->createCommand($sql2)->execute();
                    $id_planilla = Yii::$app->db->getLastInsertID('planilla');
                    $sql3 = "insert into deportista (dni,id_planilla,numero_socio) value ('$model->dni','$id_planilla','$model->numero_socio')";
                    $connection->createCommand($sql3)->execute();
                    //$connection->createCommand("insert into deportista_categoria (dni,id_categoria) value('$model->dni','$model->categoria1')")->execute();
                    foreach ($categorias as $id => $id_categoria) {
                        if ($id_categoria != "")
                            $connection->createCommand("insert into deportista_categoria (dni,id_categoria) value('$model->dni','$id_categoria')")->execute();
                    }

                    $transaction->commit();

                    $msg = "Registracion realizada con exito";
                    $model->nombre = null;
                    $model->apellido = null;
                    $model->dni = NULL;
                    $model->domicilio = null;
                    $model->telefono = null;
                    $model->telefono1 = null;
                    $model->email = null;
                    $model->numero_socio = null;

                    $model->medico_cabecera = null;
                    $model->grupo_sanguineo = null;
                    $model->obra_social = null;
                    $model->medicamento = null;
                    $model->desc_medicamento = null;
                    $model->alergia = null;
                    $model->desc_alergia = null;
                    $model->anemia = null;
                    $model->enf_cardiologica = null;
                    $model->desc_cardiologia = null;
                    $model->asma = null;
                    $model->desc_asma = null;
                    $model->presion = null;
                    $model->convulsiones = null;
                    $model->ultima_convulsion = null;
                    $model->trastornos_hemorragicos = null;
                    $model->fuma = null;
                    $model->cuanto_fuma = null;
                    $model->diabetes = null;
                    $model->desc_diabetes = null;
                    $model->tratamiento = null;
                    $model->desc_tratamiento = null;
                    $model->internaciones = null;
                    $model->desc_internacion = null;
                    $model->nombreyapellido1 = null;
                    $model->domicilio1 = null;
                    $model->telefono1 = null;
                    $model->nombreyapellido2 = null;
                    $model->domicilio2 = null;
                    $model->telefono2 = null;
                    $model->observaciones = null;
                } catch (\Exception $e) {
                    $msg = "Registracion realizada con exito";
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
        $deportes = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre_deporte');
        return $this->render("ndeportista", ["model" => $model, "msg" => $msg, 'deporte' => $deportes]);
    }

    //planilla
    //deportista
    //persona

    public function actionEliminar() {
        $msg = "null";

        if ((int) isset($_REQUEST["dni"])) {
            $dni = $_REQUEST["dni"];
            $table = Deportista::findOne($dni);
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            //$sql1="delete from deportista_categoria where dni='$dni'";
            $sql2 = "delete from planilla where id_planilla='$table->id_planilla'";
            $sql3 = "delete from deportista where dni='$dni'";
            $sql4 = "delete from persona where dni='$dni'";


            try {
                // $connection->createCommand($sql1)->execute();
                $connection->createCommand($sql2)->execute();
                $connection->createCommand($sql3)->execute();
                $connection->createCommand($sql4)->execute();
                $transaction->commit();
            } catch (\Exception $e) {
                $msg = "no se pudo realizar la eliminacion";
                $transaction->rollBack();
                throw $e;
            }
        }
        return $this->render("elideportista", ['msg' => $msg]);
    }

    public function actionOpcion($id) {
        $categorias = Categoria::find()
                ->where(['id_deporte' => $id])
                ->count();

        $categori = Categoria::find()
                ->where(['id_deporte' => $id])
                ->all();

        if ($categorias > 0) {
            foreach ($categori as $categoria) {

                echo "<option value='" . $categoria->id_categoria . "'>" . $categoria->nombre_categoria . "</option>";
            }
        } else {
            echo "<option>-</option>";
        }
    }

    public function actionModificar() {

        $model = new ValidarDeportistamodif;
        $msg = null;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (isset($_REQUEST['dni'])) {
            if ((int) $_REQUEST['dni']) {
                $model->dni = $_REQUEST['dni'];
                $table = Deportista::findOne($model->dni);
                $model->numero_socio = $table->numero_socio;
                $id = $table->id_planilla;
                $table = Persona::findOne($model->dni);
                $model->nombre = $table->nombre;
                $model->apellido = $table->apellido;
                $model->domicilio = $table->domicilio;
                $model->telefono = $table->telefono;
                $model->email = $table->email;
                $table = Planilla::findOne($id);
                $model->medico_cabecera = $table->medico_cabecera;
                $model->grupo_sanguineo = $table->grupo_sanguineo;
                $model->obra_social = $table->obra_social;
                $model->medicamento = $table->medicamento;
                $model->desc_medicamento = $table->desc_medicamento;
                $model->alergia = $table->alergia;
                $model->desc_alergia = $table->desc_alergia;
                $model->anemia = $table->anemia;
                $model->enf_cardiologica = $table->enf_cardiologica;
                $model->desc_cardiologia = $table->desc_cardiologia;
                $model->asma = $table->asma;
                $model->desc_asma = $table->desc_asma;
                $model->presion = $table->presion;
                $model->convulsiones = $table->convulsiones;
                $model->ultima_convulsion = $table->ultima_convulsion;
                $model->trastornos_hemorragicos = $table->trastornos_hemorragicos;
                $model->fuma = $table->fuma;
                $model->cuanto_fuma = $table->cuanto_fuma;
                $model->diabetes = $table->diabetes;
                $model->desc_diabetes = $table->desc_diabetes;
                $model->tratamiento = $table->tratamiento;
                $model->desc_tratamiento = $table->desc_tratamiento;
                $model->internaciones = $table->internaciones;
                $model->desc_internacion = $table->medico_cabecera;
                $model->nombreyapellido1 = $table->nombreyapellido1;
                $model->domicilio1 = $table->domicilio1;
                $model->telefono1 = $table->telefono1;
                $model->nombreyapellido2 = $table->nombreyapellido2;
                $model->domicilio2 = $table->domicilio2;
                $model->telefono2 = $table->telefono2;
                $model->observaciones = $table->observaciones;
                $table = Deportista_cat::find()->where(['dni' => $model->dni])->count();
                $cant = $table;
                switch ($table) {
                    case 1:
                        $table = Deportista_cat::find()->where(['dni' => $model->dni])->offset(0)->one();
                        $model->deporte1 = $table->id_deporte;
                        $model->categoria1 = $table->id_categoria;
                        $model->aux1 = $model->categoria1;
                        break;
                        ;
                    case 2;
                        $table = Deportista_cat::find()->where(['dni' => $model->dni])->offset(0)->one();
                        $model->deporte1 = $table->id_deporte;
                        $model->categoria1 = $table->id_categoria;
                        $table = Deportista_cat::find()->where(['dni' => $model->dni])->offset(1)->one();
                        $model->deporte2 = $table->id_deporte;
                        $model->categoria2 = $table->id_categoria;
                        $model->aux1 = $model->categoria1;
                        $model->aux2 = $model->categoria2;
                        break;
                    case 3:
                        $table = Deportista_cat::find()->where(['dni' => $model->dni])->offset(0)->one();
                        $model->deporte1 = $table->id_deporte;
                        $model->categoria1 = $table->id_categoria;
                        $table = Deportista_cat::find()->where(['dni' => $model->dni])->offset(1)->one();
                        $model->deporte2 = $table->id_deporte;
                        $model->categoria2 = $table->id_categoria;
                        $table = Deportista_cat::find()->where(['dni' => $model->dni])->offset(2)->one();
                        $model->deporte3 = $table->id_deporte;
                        $model->categoria3 = $table->id_categoria;
                        $model->aux1 = $model->categoria1;
                        $model->aux2 = $model->categoria2;
                        $model->aux3 = $model->categoria3;
                        break;
            }
        }
        }



        if (($model->load(Yii::$app->request->post()))) {
            if ($model->validate()) {

                $datos = array
                    (
                    "0" => array(
                        'deporte' => $model->deporte1,
                        'categoria' => $model->categoria1,
                        'aux' => $model->aux1
                    ),
                    "1" => array
                        (
                        'deporte' => $model->deporte1,
                        'categoria' => $model->categoria1,
                        'aux' => $model->aux1,
                    ),
                    "2" => array
                        (
                        'deporte' => $model->deporte1,
                        'categoria' => $model->categoria1,
                        'aux' => $model->aux1,
                    )
                );
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $sql1 = "UPDATE persona SET dni='$model->dni',nombre='$model->nombre',apellido='$model->apellido' where persona.dni='$model->dni' ";

                $sql2 = "update planilla,deportista set medico_cabecera='$model->medico_cabecera',grupo_sanguineo='$model->grupo_sanguineo',obra_social='$model->obra_social',medicamento='$model->medicamento',desc_medicamento='$model->desc_medicamento',
                    alergia='$model->alergia',desc_alergia='$model->desc_alergia',anemia='$model->anemia',enf_cardiologica='$model->enf_cardiologica',desc_cardiologia='$model->desc_cardiologia',asma='$model->asma',desc_asma='$model->desc_asma',presion='$model->presion',convulsiones='$model->convulsiones',ultima_convulsion='$model->ultima_convulsion',
                       trastornos_hemorragicos='$model->trastornos_hemorragicos',fuma='$model->fuma',cuanto_fuma='$model->cuanto_fuma',diabetes='$model->diabetes',desc_diabetes='$model->desc_diabetes',tratamiento='$model->tratamiento',desc_tratamiento='$model->desc_tratamiento',internaciones='$model->internaciones',desc_internacion='$model->desc_internacion',nombreyapellido1='$model->nombreyapellido1',domicilio1='$model->domicilio1',
                            telefono1='$model->telefono1',nombreyapellido2='$model->nombreyapellido2',domicilio2='$model->domicilio2',telefono2='$model->telefono2',observaciones='$model->observaciones' where planilla.id_planilla=deportista.id_planilla";

                foreach ($datos as $val) {
                    if ($val['categoria'] != "") {
                        $deporte = $val['deporte'];
                        $categoria = $val['categoria'];
                        $aux = $val['aux'];
                        $connection->createCommand("update deportista_categoria set id_categoria=$categoria, id_deporte=$deporte where dni=$model->dni and id_categoria=$aux")->execute();
                    }
                }


                try {
                    $connection->createCommand($sql1)->execute();
                    $connection->createCommand($sql2)->execute();

                    $sql3 = "UPDATE deportista SET numero_socio='$model->numero_socio' where deportista.dni='$model->dni'";
                    $connection->createCommand($sql3)->execute();
                    $transaction->commit();

                    $msg = "Modificacion realizada con exito realizada con exito";
                    $model->nombre = null;
                    $model->apellido = null;
                    $model->dni = NULL;
                    $model->domicilio = null;
                    $model->telefono = null;
                    $model->telefono1 = null;
                    $model->email = null;
                    $model->numero_socio = null;

                    $model->medico_cabecera = null;
                    $model->grupo_sanguineo = null;
                    $model->obra_social = null;
                    $model->medicamento = null;
                    $model->desc_medicamento = null;
                    $model->alergia = null;
                    $model->desc_alergia = null;
                    $model->anemia = null;
                    $model->enf_cardiologica = null;
                    $model->desc_cardiologia = null;
                    $model->asma = null;
                    $model->desc_asma = null;
                    $model->presion = null;
                    $model->convulsiones = null;
                    $model->ultima_convulsion = null;
                    $model->trastornos_hemorragicos = null;
                    $model->fuma = null;
                    $model->cuanto_fuma = null;
                    $model->diabetes = null;
                    $model->desc_diabetes = null;
                    $model->tratamiento = null;
                    $model->desc_tratamiento = null;
                    $model->internaciones = null;
                    $model->desc_internacion = null;
                    $model->nombreyapellido1 = null;
                    $model->domicilio1 = null;
                    $model->telefono1 = null;
                    $model->nombreyapellido2 = null;
                    $model->domicilio2 = null;
                    $model->telefono2 = null;
                    $model->observaciones = null;
                    $this->redirect(["deportista/buscar", 'msg' => $msg]);
                } catch (\Exception $e) {
                    $msg = "Registracion realizada con exito";
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre');
        $categoria = ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre_categoria');
        return $this->render("modificar_deportista", ["model" => $model, "msg" => $msg, 'deporte' => $deporte, 'categoria' => $categoria,'cant'=>$cant]);
    }

    public function actionBuscar($msg=null) {
        $form = new ValidarBusqueda;
        $search = null;
        
        if ($form->load(Yii::$app->request->post())) {
            
            if ($form->validate()) {
                $search = Html::encode($form->q);
                $sql = "select nombre,apellido,deportista.dni from persona "
                        . "inner join deportista on persona.dni=deportista.dni "
                        . "where deportista.dni like '%$search%' or nombre like'%$search%' or apellido like'%$search%'";
                $command = Yii::$app->db->createCommand($sql)->query();
                
                $count = clone $command;
                $pages = new Pagination(["pageSize" => 10, "totalCount" => $count->count()]);
                $sql.="limit $pages->limit offset $pages->offset";
                $command = Yii::$app->db->createCommand($sql)->queryAll();
                $model = $command;
            } else {
                $form->getErrors();
            }
        } else {
            $table = Deportista::find();
            $count = clone $table;
            $pages = new Pagination([
                "pageSize" => 10,
                "totalCount" => $count->count(),
            ]);

            $sql = "select nombre,apellido,deportista.dni from persona "
                    . "inner join deportista on persona.dni=deportista.dni "
                    . "LIMIT $pages->limit OFFSET $pages->offset";
            $command = Yii::$app->db->createCommand($sql)->queryAll();
            $model = $command;
        }
        
        return $this->render("buscar",['msg'=>$msg,'model'=>$model,'search'=>$search,'pages'=>$pages,'form'=>$form]);
    }

}
