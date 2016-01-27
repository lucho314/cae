<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ValidarRegistro;
use app\models\ValidarDeporte;
use app\models\Deporte;
use yii\helpers\ArrayHelper;
use app\models\Profesor;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Deporte_categoria;
use app\models\User;


class DeporteController extends Controller
{    public $layout='mainadmin';
        
    public function actionCrear()
    {
        $this->layout="mainadmin";
        
        $msg=null;
        $model= new ValidarDeporte;
        
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                $tabla= new Deporte;
                $tabla->nombre=  strtolower($model->nombre);
                if($tabla->insert())
                {
                    $msg="Depote registrado con exito";
                    $model->nombre=null;
                }
                
                else
                {
                    $msg="problemas al cargar en base";
                }
            }
            else
            {
                
                $model->getErrors();

            }
            
        }
        
        return $this->render("nuevo_deporte",['model'=>$model,'msg'=>$msg]); 
            
            
    }
    
    public function actionModificar()
    {
        $this->layout="mainadmin";
        $model= new ValidarDeporte;
        $msg=null;
                
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (($model->load(Yii::$app->request->post())))
        {
            if($model->validate())
            {
                $tabla=  Deporte::findOne($model->deporte);
                
                $tabla->nombre=$model->nombre;
                if($tabla->update())
                {
                    $msg="$model->deporte";
                    $model->nombre=null;
                }
                else
                {
                    $msg="no se pudo actualizar";
                }
            }
            else
            {
                $model->getErrors();
            }
        }
           
        $deporte=ArrayHelper::map(Deporte::find()->all(),'id_deporte','nombre');
       return $this->render("modificar_deporte",["msg"=>$msg,"model"=>$model,"deporte"=>$deporte]);
    }
    
    public function actionEliminar()
    {
        $msg=null;
        if((int)isset($_REQUEST["id_deporte"]))
        {
            $tabla=Deporte::findOne($_REQUEST['id_deporte']);
            if($tabla->delete())
            {
                $msg="Eliminacion realizada con exito!";
            }
            else
            {
                $msg="No se pudo realizar la eliminacion";
            }
            
        }
         $deporte=  Deporte::find()->all();
        return $this->render("eliminar_deporte",["msg"=>$msg,'deporte'=>$deporte]);
    }
    
   
    
                
                public function actionElegir()
                {
                    $dni=null;
                    if(isset($_REQUEST['dni']))
                    {   
                        $dni=$_REQUEST['dni'];
                        $this->redirect(["deporte/nclase",'dni'=>$dni]);
                    }
                    $profesor=ArrayHelper::map(Profesor::find()->all(),'dni','nombre');
                    return $this->render("pruebacat",['profesor'=>$profesor]);
                }

     public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['crear','modificar','eliminar','elejir'],
                'rules' => [
                    [
                        'actions' => ['crear','modificar','eliminar','elejir'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) 
                        {
                          return User::isUserAdmin(Yii::$app->user->identity->id);
                        }
                    ],
                            
                    [
                        'actions' => [],
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
     
}
