<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\models\Comision;

class ValidarComision extends model
{
    public $id_comision;
    public $dia;
    public $nombre_comision;
    public $categoria;
    public $hora_inicio;
    public $hora_fin;


    public function rules()
    {
        return
        [
            ['nombre_comision', 'required', 'message' => 'Campo requerido'], 
            ['nombre_comision', 'match', 'pattern' => "/^.{3,50}$/", 'message' => 'Mínimo 3 y máximo 50 caracteres'],
            ['nombre_comision', 'match', 'pattern' => "/^[0-9a-z]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['hora_fin', 'compare', 'compareAttribute'=>'hora_inicio','operator'=> '>', 'message'=>'El horario de fin tiene que ser mayor que el de inicio'],
            ['categoria','required' ,'message'=>'Campo requerido'],
            ['id_comision','number'],
            ["dia","required"],
            ['hora_inicio','required',"message"=>"campo requerido"],
            ['hora_inicio','required',"message"=>"campo requerido"],
            ['hora_inicio',"match",'pattern'=>"/^((0+[0-9])|1\d|2+[0-3])+:+([0-5]\d)+:+([0-5]\d)$/","message"=>"Hora incorrecta"],
            ['hora_fin',"match",'pattern'=>"/^((0+[0-9])|1\d|2+[0-3])+:+([0-5]\d)+:+([0-5]\d)$/","message"=>"Hora incorrecta"]
        ];  
    }
    
}
