<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\LoginForm;
use app\models\Product;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Elimina la autenticación por defecto para definir una personalizada
        unset($behaviors['authenticator']);

        // Agrega el filtro CORS para permitir peticiones desde otros orígenes
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        // Agrega autenticación por Bearer Token para acciones específicas
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['login', 'options'], // El login es público
        ];

        return $behaviors;
    }

    /**
     * Endpoint de inicio de sesión.
     * Retorna el token de acceso.
     * Método: POST
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $user = $model->getUser();

            // Genera un token si el usuario aún no tiene uno
            if (empty($user->access_token)) {
                $user->access_token = Yii::$app->security->generateRandomString();
                $user->save(false);
            }

            return [
                'token' => $user->access_token,
                'user_id' => $user->id,
                'username' => $user->username,
            ];
        }

        // Retorna los errores de validación si el login falla
        return $model;
    }

    /**
     * Endpoint para listar productos.
     * Protegido por autenticación.
     * Método: GET
     */
    public function actionProducts()
    {
        return Product::find()->all();
    }
}
