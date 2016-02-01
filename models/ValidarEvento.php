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
         public $convocados;




         public function rules(){
         
        return 
         [
          [['nombre','condicion','fecha','id_profesor_titular','convocados','id_profesor_suplente','id_deporte'], 'required', 'message'=>'campo requerido'],
         ];
        
    }
    
}
