<?php

namespace app\models;

use yii\base\Model;

Class ValidarDeportista extends model {
    
    public $nombre;
    public $apellido;
    public $dni;
    public $domicilio;
    public $telefono;
    public $email;
    public $grupo_sanguineo;
    public $medico_cabecera;
    public $obra_social;
    public $numero_socio;
    public $medicamento="no";
    public $desc_medicamento;
    public $anemia="no";
    public $alergia="no";
    public $desc_alergia;
    public $enf_cardiologica="no";
    public $desc_cardiologia;
    public $asma="no";
    public $desc_asma;
    public $convulsiones="no";
    public $ultima_convulsion;
    public $fuma="no";
    public $cuanto_fuma;
    public $trastornos_hemorragicos="no";
    public $diabetes="no";
    public $desc_diabetes;
    public $tratamiento="no";
    public $desc_tratamiento;
    public $presion="normal";
    public $nombreyapellido1;
    public $domicilio1;
    public $telefono1;
    public $nombreyapellido2;
    public $domicilio2;
    public $telefono2;
    public $observaciones;
    public $internaciones;
    public $desc_internacion;
    public $deporte1;
    public $deporte2;
    public $deporte3;
    public $categoria1;
    public $categoria2;
    public $categoria3;
    public $deportista;


    public $file;

    public function rules() {
        return [
            ['file', 'file',
                'skipOnEmpty' => false,
                'uploadRequired' => 'No has seleccionado ningún archivo', //Error
                'maxSize' => 1024 * 1024 * 1, //1 MB
                'tooBig' => 'El tamaño máximo permitido es 1MB', //Error
                'minSize' => 10, //10 Bytes
                'tooSmall' => 'El tamaño mínimo permitido son 10 BYTES', //Error
                'extensions' => 'JPG',
                'wrongExtension' => 'El archivo {file} no contiene una extensión permitida {extensions}', //Error
                'maxFiles' => 4,
                'tooMany' => 'El máximo de archivos permitidos son {limit}', //Error
            ],
            [['nombre', 'apellido', 'domicilio', 'telefono', 'dni', 'grupo_sanguineo', 'medico_cabecera', 'email', 'obra_social', 'numero_socio','observaciones'], 'required', 'message' => 'Campo requerido'],
            ['nombre', 'match', 'pattern' => "/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 20 caracteres'],
            ['nombre', 'match', 'pattern' => "/^[a-záéíóúñ\s]+$/i", 'message' => 'Sólo se aceptan letras'],
            ['apellido', 'match', 'pattern' => "/^.{3,30}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            ['apellido', 'match', 'pattern' => "/^[a-záéíóúñ\s]+$/i", 'message' => 'Sólo se aceptan letras'],
            ['domicilio', 'match', 'pattern' => "/^.{1,50}$/", 'message' => 'ah superado el maximo de 50 caracteres'],
            ['domicilio', 'match', 'pattern' => "/^[0-9a-z\s]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['telefono', 'number', 'message' => 'solo se aceptan numeros'],
            ['telefono', 'match', 'pattern' => "/^.{10,}$/", 'message' => 'Numero de telefono incorrecto'],
            ['dni', 'number', 'message' => 'solo se aceptan numeros'],
            ['dni', 'match', 'pattern' => "/^.{8,9}$/", 'message' => 'formato de DNI incorrecto'],
            ['dni', 'dni_existe'],
            ['email', 'required', 'message' => 'Campo requerido'],
            ['email', 'match', 'pattern' => "/^.{5,80}$/", 'message' => 'Mínimo 5 y máximo 80 caracteres'],
            ['email', 'email', 'message' => 'Formato no válido'],
            ['email', 'email_existe'],
            ['categoria1', 'number'],
            ['categoria2', 'number'],
            ['categoria3', 'number'],

            [['medicamento','anemia','alergia','enf_cardiologica',
                'asma','convulsiones','fuma','trastornos_hemorragicos',
                'diabetes','presion','tratamiento'], 'required', 'message' => 'Campo requerido'],

             
            [ 'desc_medicamento','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'desc_alergia','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'desc_cardiologia','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'desc_asma','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'ultima_convulsion','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'desc_medicamento','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'cuanto_fuma','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'desc_diabetes','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            [ 'desc_tratamiento','match','pattern'=>"/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],

            
            [['nombreyapellido1', 'domicilio1', 'telefono1', 'nombreyapellido2', 'domicilio2', 'telefono2'], 'required', 'message' => 'campo requerido'],
            ['nombreyapellido1', 'match', 'pattern' => "/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 20 caracteres'],
            ['nombreyapellido1', 'match', 'pattern' => "/^[a-záéíóúñ\s]+$/i", 'message' => 'Sólo se aceptan letras'],
            ['nombreyapellido2', 'match', 'pattern' => "/^.{3,20}$/", 'message' => 'Mínimo 3 y máximo 20 caracteres'],
            ['nombreyapellido2', 'match', 'pattern' => "/^[a-záéíóúñ\s]+$/i", 'message' => 'Sólo se aceptan letras'],
            ['domicilio1', 'match', 'pattern' => "/^.{1,50}$/", 'message' => 'ah superado el maximo de 50 caracteres'],
            ['domicilio1', 'match', 'pattern' => "/^[0-9a-z\s]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['telefono1', 'number', 'message' => 'solo se aceptan numeros'],
            ['telefono1', 'match', 'pattern' => "/^.{10,}$/", 'message' => 'Numero de telefono incorrecto'],
            ['domicilio2', 'match', 'pattern' => "/^.{1,50}$/", 'message' => 'ah superado el maximo de 50 caracteres'],
            ['domicilio2', 'match', 'pattern' => "/^[0-9a-z\s]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['telefono2', 'number', 'message' => 'solo se aceptan numeros'],
            ['telefono2', 'match', 'pattern' => "/^.{10,}$/", 'message' => 'Numero de telefono incorrecto'],
            [['deporte2','deporte3'], 'compare', 'compareAttribute' => 'deporte1','operator' => '!==', 'message' => 'No se pueden repetir los deportes'],
            [['deporte2','deporte1'], 'compare', 'compareAttribute' => 'deporte3','operator' => '!==', 'message' => 'No se pueden repetir los deportes'],  
        ];
    }
    
   
    
    public function email_existe($attribute, $params) {


        $table = Persona::find()->where("email=:email", [":email" => $this->email]);


        if ($table->count() != 0) {
            $this->addError($attribute, "El email seleccionado existe");
        }
    }
    
    
    public function dni_existe($attribute, $params) {
        $table = Persona::find()->where("dni=:dni", [":dni" => $this->dni]);

        if ($table->count() != 0)
            $this->addError($attribute, "El dni seleccionado existe");
    }

}
