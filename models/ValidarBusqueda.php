<?php
namespace app\models;
use yii;
use yii\base\Model;



class ValidarBusqueda extends model{
    public $q;
    
    public function rules()
    {
        return [
            ["q", "match", "pattern" => "/^[0-9a-záéíóúñ\s]+$/i", "message" => "Sólo se aceptan letras y números"]
        ];
    }
    
    
}