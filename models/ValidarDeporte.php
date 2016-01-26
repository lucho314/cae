<?php

namespace app\models;
use yii;
use yii\base\Model;
use app\models\Deporte;

class ValidarDeporte extends model
{
    public $nombre;
    public $deporte;


    public function rules()
    {
       
        return 
         [
          ['nombre' , 'required', 'message'=>'campo requerido'],
          ['nombre', 'match', 'pattern' => "/^[a-záéíóúñ\s]+$/i", 'message' => 'Sólo se aceptan letras'],
          ['nombre', 'match', 'pattern' => "/^.{3,10}$/", 'message' => 'Mínimo 3 y máximo 10 caracteres'],
          ['nombre','val_nombre'],
          ['deporte','number']
         ];
    }
    
    public function val_nombre($attribute, $params)
    {
        $table=  Deporte::find()->where("nombre=:n",[":n"=>  $this->nombre]);
        if($table->count()!= 0)
        {
            $this->addError($attribute,"el deporte ingresado ya existe");
        }
    }
}