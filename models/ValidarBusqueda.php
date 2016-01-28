<?php
namespace app\models;
use yii;
use yii\base\Model;



class ValidarBusqueda extends model{
    public $q;
    public $desde;
    public $hasta;
    public function rules()
    {
        return [
            ['q','match', 'pattern' => "/^.{2,20}$/", 'message' => 'Mínimo 2 y máximo 20 caracteres'],
            ['desde','match', 'pattern' => "/^.{6,20}$/", 'message' => 'Mínimo 3 y máximo 20 caracteres'],
            ['hasta','match', 'pattern' => "/^.{6,20}$/", 'message' => 'Mínimo 3 y máximo 20 caracteres'],
            
        ];
    }
    
    
}