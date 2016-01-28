<?php
namespace app\models;
use yii;
use yii\base\Model;
use app\models\Evento;

class ValidarEvento extends model{
         
         public $id_evento;
         public $nombre;
         public $condicion;
         public $fecha;
         public $id_profesor_titular;
         public $id_profesor_suplente;
         public $id_deporte;
         public $id_categoria;
         public $id_lista;
         
                    
    public function rules(){
         
        return 
         [
          ['nombre' , 'required', 'message'=>'campo requerido'],
          ['condicion' , 'required', 'message'=>'campo requerido'],
          ['fecha' , 'required', 'message'=>'campo requerido'],   
          ['id_profesor_titular' , 'required', 'message'=>'campo requerido'],   
          ['id_profesor_suplente' , 'required', 'message'=>'campo requerido'],
          ['id_deporte' , 'required', 'message'=>'campo requerido'],   
          ['id_categoria' , 'required', 'message'=>'campo requerido'],   
          ['id_lista' , 'required', 'message'=>'campo requerido'],   
        
         ];
        
    }
    
}
