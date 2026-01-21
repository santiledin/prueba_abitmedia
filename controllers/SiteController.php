<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // Control de acceso para acciones específicas
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'], // Solo usuarios autenticados pueden cerrar sesión
                    ],
                ],
            ],

            // Restricción de métodos HTTP por acción
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'], // Cerrar sesión solo mediante POST
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            // Acción para manejar errores de la aplicación
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Muestra la página principal
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        // Si el usuario no ha iniciado sesión, redirige al login
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        // Si está autenticado, redirige al listado de productos
        return $this->redirect(['product/index']);
    }

    /**
     * Acción de inicio de sesión
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        // Si el usuario ya está autenticado, redirige a la página principal
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        // Procesa el formulario de login
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        // Limpia el campo de contraseña por seguridad
        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Acción de cierre de sesión
     *
     * @return Response
     */
    public function actionLogout()
    {
        // Cierra la sesión del usuario
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Muestra la página de contacto
     *
     * @return Response|string
     */
}
