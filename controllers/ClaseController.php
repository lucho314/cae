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
class ClaseController extends Controller
{
    public $layout='mainadmin';
    
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['crear','modificar','buscar'],
                'rules' => [
                    [
                        'actions' => ['crear'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) 
                        {
                          return User::isUserAdmin(Yii::$app->user->identity->id);
                        }
                    ],
                            
                    [
                        'actions' => ['crear'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) 
                        {
                            return User::isUserProfe(Yii::$app->user->identity->id);
                        }

                    ],
                    [
                        'actions' => [],
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
     
    
    
    public function actionCrear(){
        $this->layout='mainadmin';
        $msg=null;
        $model= new ValidarClase;
        
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                $tabla= new Clase;
                $tabla->fecha=$model->fecha;
                $tabla->observaciones=$model->observaciones;
                $tabla->id_comision=$model->comision;
                
                if($tabla->insert())
                {
                    $msg="Clase registrada con exito!";
                    $model->fecha=null;
                    $model->observaciones=null;
                    $model->comision=null;
                    
                }
                else
                {
                    $msg="No se pudo registrar Clase";
                }
                }
                    }
                    
                   
                 $dni=Yii::$app->user->identity->id;  
                 $comisiones=ArrayHelper::map
                         (
                          Prof_Comision::find()
                            ->where(['id_profesor_titular'=>$dni])
                            ->orWhere(['id_profesor_titular'=>$dni])
                            ->all(),
                         'id_comision','nombre'
                         );   

                           return $this->render("nclase",['model'=>$model,'msg'=>$msg,'comisiones'=>$comisiones]); 

                }
}