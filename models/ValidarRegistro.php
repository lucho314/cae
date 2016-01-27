<?php

namespace app\models;
use yii;
use yii\base\Model;
use app\models\Persona;
use app\models\Users;

class ValidarRegistro extends model{
    public $nombre;
    public $apellido;
    public $domicilio;
    public $telefono;
    public $dni;
    public $nombre_usuario;
    public $contrasenia;
    public $email;
    public $conf_cont;


    public function rules()
    {
        return [
            [['nombre','apellido','domicilio','telefono','dni','nombre_usuario','contrasenia','email'], 'required', 'message' => 'Campo requerido'],
            ['nombre', 'match', 'pattern' => "/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 20 caracteres'],
            ['nombre', 'match', 'pattern' => "/^[a-záéíóúñ\s]+$/i", 'message' => 'Sólo se aceptan letras'],
            ['apellido', 'match', 'pattern' => "/^.{3,30}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            ['apellido', 'match', 'pattern' => "/^[a-záéíóúñ\s]+$/i", 'message' => 'Sólo se aceptan letras'],
            ['domicilio', 'match', 'pattern' => "/^.{1,50}$/", 'message' => 'ah superado el maximo de 50 caracteres'],
            ['domicilio', 'match', 'pattern' => "/^[0-9a-z\s]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['telefono','number','message'=>'solo se aceptan numeros'],
            ['telefono','match', 'pattern' => "/^.{10,}$/", 'message' => 'Numero de telefono incorrecto'],
            ['dni','number','message'=>'solo se aceptan numeros'],
            ['dni','match', 'pattern' => "/^.{8,9}$/", 'message' => 'formato de DNI incorrecto'],
            ['dni', 'dni_existe'],
            ['nombre_usuario', 'match', 'pattern' => "/^.{3,8}$/", 'message' => 'Mínimo 3 y máximo 9 caracteres'],
            ['nombre_usuario', 'match', 'pattern' => "/^[0-9a-z\s]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['nombre_usuario', 'usuario_existe'],
            ['contrasenia','match', 'pattern' => "/^.{6,20}$/", 'message' => 'Mínimo 6 y máximo 20 caracteres'],
            ['contrasenia', 'match', 'pattern' => "/^[0-9a-z\s]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['email', 'required', 'message' => 'Campo requerido'],
            ['email', 'match', 'pattern' => "/^.{5,80}$/", 'message' => 'Mínimo 5 y máximo 80 caracteres'],
            ['email', 'email', 'message' => 'Formato no válido'],
            ['email', 'email_existe'],
            ['conf_cont', 'compare', 'compareAttribute' => 'contrasenia', 'message' => 'Las contraseñas no coinciden']
           
            
        ];
    }
    
    public function attributeLabels()
    {
        return
        [
            'nombre'=>"Ingrese su nombre:",
            'apellido'=>"Ingrese su apellido:",
            'domicilio'=>"Ingrese Domicilio:",
            'telefono'=>"Ingrese Telefono:",
            'dni'=>"Ingrese DNI:",
            'nombre_usuario'=>"ingrese Usuario",
            'contrasenia'=>"Ingrese su Contraseña:",
            'email'=>"ingrese Email:",
            'conf_cont'=>"ingrese contraseña:"
            
        ];
    }
     
     public function email_existe($attribute, $params)
    {
  
        //Buscar el email en la tabla
        $table = Persona::find()->where("email=:email", [":email" => $this->email]);

        //Si el email existe mostrar el error
        if ($table->count() != 0)
        {
                      $this->addError($attribute, "El email seleccionado existe");
        }
    }
    
    public function dni_existe($attribute, $params)
    {
        $table=Persona::find()->where("dni=:dni",[":dni"=>  $this->dni]);
        
        if($table->count() != 0) $this->addError($attribute,"El dni seleccionado existe");
    }
 
    public function usuario_existe($attribute, $params)
    {
        $table=  Users::find()->where(['nombre_usuario'=>$this->nombre_usuario]);
        
        if($table->count() != 0) 
        {
            $this->addError($attribute,"El nobre de usuario seleccionado existe");
            return true;
        }
        return false;   
    }
}
