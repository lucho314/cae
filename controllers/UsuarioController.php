<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\ValidarProfesor;
use yii\helpers\ArrayHelper;
use app\models\Deporte;
use app\models\LoginForm;
use app\models\Users;
use yii\web\Session;
use app\models\ValidarReset;
use app\models\ValidarRecuperacion;
use yii\helpers\Url;
use app\models\User;
use app\models\ValidarRegistro;

class UsuarioController extends Controller {

    public $layout;

    public function actionIndex() {
        return $this->redirect(["usuario/login"]);
    }

    public function actionLogin() {
        $nombre = null;
        if (!\Yii::$app->user->isGuest) {
            $nombre = $nombre = Yii::$app->user->identity->nombre_usuario;
            if (User::isUserAdmin(Yii::$app->user->identity->id)) {
                $this->redirect(['usuario/admin']);
            }
            if (User::isUserProfe(Yii::$app->user->identity->id)) {
                $this->redirect(['usuario/profesor']);
            }
            if (User::isUserSubcomision(Yii::$app->user->identity->id)) {
                $this->redirect(['usuario/subcomision']);
            }
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $nombre = Yii::$app->user->identity->nombre_usuario;
            if (User::isUserAdmin(Yii::$app->user->identity->id)) {
                $this->redirect(['usuario/admin']);
            }
            if (User::isUserProfe(Yii::$app->user->identity->id)) {
                $this->redirect(['usuario/profesor']);
            }
            if (User::isUserSubcomision(Yii::$app->user->identity->id)) {
                $this->redirect(['usuario/subcomision']);
            }
        }


        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    private function randKey($str = '', $long = 0) {
        $key = null;
        $str = str_split($str);
        $start = 0;
        $limit = count($str) - 1;
        for ($x = 0; $x < $long; $x++) {
            $key .= $str[rand($start, $limit)];
        }
        return $key;
    }

    public function actionNuevo() {
        $this->layout = "mainadmin";
        if (isset($_REQUEST["tipo"])) {
            switch ($_REQUEST['tipo']) {
                case 1:
                    $this->redirect("index.php?r=usuario/crearadmin");
                    break;
                case 2:
                    $this->redirect("index.php?r=usuario/crearsubcomision");
                    break;
                case 3:
                    $this->redirect("index.php?r=usuario/crearprofesor");
                    break;
            }
        }


        return $this->render("nuevo_usuario");
    }

    public function actionCrearadmin() {
        $this->layout = "mainadmin";
        $msg = null;
        $model = new ValidarRegistro;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $password = crypt($model->contrasenia, Yii::$app->params["salt"]);
                $authKey = $this->randKey("abcdef0123456789", 200);
                $accessToken = $this->randKey("abcdef0123456789", 200);
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $sql1 = "insert into persona (dni,nombre,apellido,domicilio,telefono,email) value ('$model->dni','$model->nombre','$model->apellido','$model->domicilio','$model->telefono','$model->email')";
                $sql2 = "insert into usuario (dni,nombre_usuario,contrasenia,privilegio,authKey,accessToken) value ('$model->dni','$model->nombre_usuario','$password',1,'$authKey','$accessToken')";
                try {
                    $connection->createCommand($sql1)->execute();
                    $connection->createCommand($sql2)->execute();
                    $transaction->commit();
                    $msg = "Registracion realizada con exito";
                    $model->nombre = null;
                    $model->apellido = null;
                    $model->dni = NULL;
                    $model->domicilio = null;
                    $model->nombre_usuario = null;
                    $model->conf_cont = null;
                    $model->contrasenia = null;
                    $model->telefono = null;
                    $model->email = null;
                } catch (\Exception $e) {
                    $msg = "Registracion realizada con exito";
                    $transaction->rollBack();
                    throw $e;
                }
            }

            
        }
        return $this->render("nuevo_admin", ["model" => $model, "msg" => $msg]);
    }

    public function actionCrearprofesor() {
        $this->layout = "mainadmin";
        $msg = null;
        $model = new ValidarProfesor;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $password = crypt($model->contrasenia, Yii::$app->params["salt"]);
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $sql1 = "insert into persona (dni,nombre,apellido,domicilio,telefono,email) value ('$model->dni','$model->nombre','$model->apellido','$model->domicilio','$model->telefono','$model->email')";
                $sql2 = "insert into usuario (dni,nombre_usuario,contrasenia,privilegio) value ('$model->dni','$model->nombre_usuario','$password',3)";
                $sql3 = "insert into profesor (dni) value ('$model->dni')";
                try {
                    $connection->createCommand($sql1)->execute();
                    $connection->createCommand($sql2)->execute();
                    $connection->createCommand($sql3)->execute();
                    foreach ($model->deportes as $deporte) {
                        $connection->createCommand("insert into profesor_deporte (dni,id_deporte) value ($model->dni,$deporte)")->execute();
                    }
                    $transaction->commit();
                    $msg = "Registracion realizada con exito";
                    $model->nombre = null;
                    $model->apellido = null;
                    $model->dni = NULL;
                    $model->domicilio = null;
                    $model->nombre_usuario = null;
                    $model->conf_cont = null;
                    $model->contrasenia = null;
                    $model->telefono = null;
                    $model->email = null;
                    $model->deportes = null;
                } catch (\Exception $e) {
                    $msg = "Registracion realizada con exito";
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre_deporte');
        return $this->render("nuevo_profesor", ["model" => $model, "msg" => $msg, 'deporte' => $deporte]);
    }

    public function actionCrearsubcomision() {

        $this->layout = "mainadmin";
        $msg = null;
        $model = new ValidarProfesor;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $password = crypt($model->contrasenia, Yii::$app->params["salt"]);
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $sql1 = "insert into persona (dni,nombre,apellido,domicilio,telefono,email) value ('$model->dni','$model->nombre','$model->apellido','$model->domicilio','$model->telefono','$model->email')";
                $sql2 = "insert into usuario (dni,nombre_usuario,contrasenia,privilegio) value ('$model->dni','$model->nombre_usuario','$password',2)";
                $sql3 = "insert into sub_comision (dni,id_deporte) value ('$model->dni','$model->deportes')";

                //$this->Correo($model);
                try {
                    $connection->createCommand($sql1)->execute();
                    $connection->createCommand($sql2)->execute();
                    $connection->createCommand($sql3)->execute();
                    $transaction->commit();
                    $msg = "Registracion realizada con exito";
                    $model->nombre = null;
                    $model->apellido = null;
                    $model->dni = NULL;
                    $model->domicilio = null;
                    $model->nombre_usuario = null;
                    $model->conf_cont = null;
                    $model->contrasenia = null;
                    $model->telefono = null;
                    $model->email = null;
                    $model->deportes = null;
                } catch (\Exception $e) {
                    $msg = "Registracion realizada con exito";
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
        $deporte = ArrayHelper::map(Deporte::find()->all(), 'id_deporte', 'nombre_deporte');
        return $this->render("nueva_subcomision", ["model" => $model, "msg" => $msg, 'deporte' => $deporte]);
    }

    public function Correo($model) {
        $subject = "Confirmar registro";
        $body = "<h1>Ah sido dado de alta en la poronga del cae</h1>";
        $body .= "<a >Usuario: $model->nombre_usuario</a>";
        $body .="<a>Contraseña: $model->contrasenia </a>";
        Yii::$app->mailer->compose()
                ->setTo($model->email)
                ->setFrom([Yii::$app->params["adminEmail"] => Yii::$app->params["title"]])
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'nuevo', 'crearadmin', 'crearsubcomision', 'crearprofesor'],
                'rules' => [
                    [
                        'actions' => ['logout', 'login', 'nuevo', 'crearadmin', 'crearsubcomision', 'crearprofesor'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserAdmin(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserProfe(Yii::$app->user->identity->id);
                }
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                    return User::isUserSubcomision(Yii::$app->user->identity->id);
                }
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        $this->redirect(['/usuario/login']);
    }

    public function actionResetcontra() {
        //Instancia para validar el formulario
        $model = new ValidarReset;

        //Mensaje que será mostrado al usuario
        $msg = null;

        //Abrimos la sesión
        $session = new Session;
        $session->open();
        if (empty($session["recover"]) || empty($session["dni"])) {
            return $this->redirect(["site/index"]);
        } else {

            $recover = $session["recover"];
            //El valor de esta variable de sesión la cargamos en el campo recover del formulario
            $model->recover = $recover;

            //Esta variable contiene el id del usuario que solicitó restablecer el password
            //La utilizaremos para realizar la consulta a la tabla users
            $id_recover = $session["dni"];
        }
        //Si el formulario es enviado para resetear el password
        if ($model->load(Yii::$app->request->post())) {



            if ($model->validate()) {
                //Si el valor de la variable de sesión recover es correcta
                if ($recover == $model->recover) {
                    //Preparamos la consulta para resetear el password, requerimos el email, el id 
                    //del usuario que fue guardado en una variable de session y el código de verificación
                    //que fue enviado en el correo al usuario y que fue guardado en el registro
                    $table = Users::findOne(["email" => $model->email, "dni" => $id_recover, "verification_code" => $model->codigo]);

                    //Encriptar el password
                    $table->contrasenia = crypt($model->contrasenia, Yii::$app->params["salt"]);

                    //Si la actualización se lleva a cabo correctamente
                    if ($table->save()) {

                        //Destruir las variables de sesión
                        $session->destroy();

                        //Vaciar los campos del formulario
                        $model->email = null;
                        $model->contrasenia = null;
                        $model->conf_cont = null;
                        $model->recover = null;
                        $model->codigo = null;

                        $msg = "Enhorabuena, password reseteado correctamente, redireccionando a la página de login ...";
                        $msg .= "<meta http-equiv='refresh' content='5; " . Url::toRoute("usuario/login") . "'>";
                    } else {
                        $msg = "Ha ocurrido un error";
                    }
                } else {
                    $this->getErrors;
                }
            }
        }
        return $this->render("recuperar_cuenta", ["model" => $model, "msg" => $msg]);
    }

    public function actionRecuperar_cuenta() {

        $model = new ValidarRecuperacion;  //Instancia para validar el formulario
        $msg = null; //mesage que se le mostrara al usuario en la vista
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $tabla = Users::find()->where(['email' => $model->email]); //Buscar al usuario a través del email

                if ($tabla->count() == 1) {//Si el usuario existe
                    //Crear variables de sesión para limitar el tiempo de restablecido del password
                    //hasta que el navegador se cierre
                    $session = new Session;
                    $session->open();
                    //Esta clave aleatoria se cargará en un campo oculto del formulario de reseteado
                    $session["recover"] = $this->randKey("abcdef0123456789", 200);
                    $recover = $session["recover"];
                    //También almacenaremos el dni del usuario en una variable de sesión
                    //El dni del usuario es requerido para generar la consulta a la tabla users y 
                    //restablecer el password del usuario
                    $tabla = Users::find()->where(['email' => $model->email])->one();
                    $session["dni"] = $tabla->dni;
                    //Esta variable contiene un número hexadecimal que será enviado en el correo al usuario 
                    //para que lo introduzca en un campo del formulario de reseteado
                    //Es guardada en el registro correspondiente de la tabla users
                    $verification_code = $this->randKey("abcdef0123456789", 8);
                    //Columna verification_code
                    $tabla->verification_code = $verification_code;
                    //Guardamos los cambios en la tabla users
                    $tabla->save();

                    //Creamos el mensaje que será enviado a la cuenta de correo del usuario
                    $subject = "Recuperar password";
                    $body = "<p>Copie el siguiente código de verificación para restablecer su password ... ";
                    $body .= "<strong>" . $verification_code . "</strong></p>";
                    $body .= "<p><a href='http://localhost/basic/web/index.php?r=usuario/resetcontra'>Recuperar password</a></p>";
                    //Enviamos el correo
                    Yii::$app->mailer->compose()
                            ->setTo($model->email)
                            ->setFrom([Yii::$app->params["adminEmail"] => Yii::$app->params["title"]])
                            ->setSubject($subject)
                            ->setHtmlBody($body)
                            ->send();
                    $model->email = null;

                    //Mostrar el mensaje al usuario
                    $msg = "Le hemos enviado un mensaje a su cuenta de correo para que pueda resetear su password";
                } else {
                    $msg = "ocurrio un error";
                }
            } else {
                $this->getErrors();
            }
        }
        return $this->render("recuperar_contrasenia", ["model" => $model, "msg" => $msg]);
    }

    public function actionAdmin() {
        $this->layout = "mainadmin";
        $nombre = Yii::$app->user->identity->nombre_usuario;
        return $this->render("inicio", ['nombre' => $nombre]);
    }

    public function actionProfesor() {
        $this->layout = "mainprofe";
        $nombre = Yii::$app->user->identity->nombre_usuario;
        return $this->render("inicio", ['nombre' => $nombre]);
    }

    public function actionSubcomision() {
        $this->layout = "mainsubcomision";
        $nombre = Yii::$app->user->identity->nombre_usuario;
        return $this->render("inicio", ['nombre' => $nombre]);
    }
    
    public function actionVer()
    {
        $table=Users::find()->all();
        $privilegio=array('administrador','sub comision','profesor');
        list()=$privilegio;
    }

    public function actionError()
    {
        return $this->render("errorjava");
    }
}
