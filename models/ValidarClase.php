<?php

namespace app\models;
use yii;
use yii\base\Model;
use app\models\Clase;



class ValidarClase extends model
{
    public $fecha;
    public $observaciones;
    public $comision;


    public function rules()
    {
        return 
        [
            ['fecha','required', 'message'=>'campo requerido'],
            ['observaciones', 'match', 'pattern' => "/^[0-9a-z\s]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['comision','required']
        ];
    }
    
    
    
}