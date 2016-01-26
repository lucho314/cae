<?php
 
namespace app\models;

use yii;
use yii\base\Model;

 
class ValidarReset extends model{
 
    public $email;
    public $contrasenia;
    public $conf_cont;
    public $codigo;
    public $recover;
     
    public function rules()
    {
        return [
            [['email', 'contrasenia', 'conf_cont', 'codigo', 'recover'], 'required', 'message' => 'Campo requerido'],
            ['email', 'match', 'pattern' => "/^.{5,80}$/", 'message' => 'Mínimo 5 y máximo 80 caracteres'],
            ['email', 'email', 'message' => 'Formato no válido'],
            ['contrasenia', 'match', 'pattern' => "/^.{8,16}$/", 'message' => 'Mínimo 6 y máximo 16 caracteres'],
            ['conf_cont', 'compare', 'compareAttribute' => 'contrasenia', 'message' => 'Los passwords no coinciden'],
        ];
    }
}