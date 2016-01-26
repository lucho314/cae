<?php

namespace app\models;
use yii;
use yii\base\Model;

class ValidarCategoria extends model
{
    public $id_categoria;
    public $nombre_categoria;
    public $edad_max;
    public $edad_min;
    public $deporte;
    public $profesor_titular;
    public $profesor_suplente;
    public $categoria;






    public function rules()
    {
        return 
        [
            ['nombre_categoria','required', 'message'=>'campo requerido'],
            ['edad_max','required', 'message'=>'campo requerido'],
            ['edad_min','required', 'message'=>'campo requerido'],
            ['id_categoria','number'],
            ['edad_max','val_edad'],
            ['nombre_categoria', 'match', 'pattern' => "/^[0-9a-z]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['nombre_categoria', 'match', 'pattern' => "/^.{3,10}$/", 'message' => 'Mínimo 3 y máximo 10 caracteres'],
            [['edad_max','edad_min'],'number','message'=>'solo se aceptan numeros'],
            ['profesor_titular', 'required'],
            ['profesor_suplente','rep_prof'],
            ['deporte','number']
            
        ];
    }
    
     public function attributeLabels()
    {
        return
        [
            'nombre'=>"Ingrese Categoria:",
            'edad_max'=>"Ingrese la edad Maxima:",
            'edad_min'=>"Ingrese la edad Minima:",
            'deporte'=>"seleccione el Deporte",
            'profesor_titular'=>"seleccionar profesor titular",
            'profesor_suplente'=>"seleccionar profesor suplente"
            
        ];
    }
    public function rep_prof($attribute,$params)
    {
       if($this->profesor_titular==$this->profesor_suplente)
       {
           $this->addError($attribute, "Profesor titular y suplente deben ser diferentes");
           return true;
       }
       return false;
    }
 
        public function val_edad($attribute,$params)
    {
       if($this->edad_max<=$this->edad_min)
       {
           $this->addError($attribute, "La edad Maxima debe ser mayor o igual a la edad Minima");
           return true;
       }
       return false;
    }
    
}
