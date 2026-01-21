<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            // Control de acceso basado en autenticación y permisos (RBAC)
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Solo usuarios autenticados
                        // RF-06: Control de acceso mediante permisos
                        'matchCallback' => function ($rule, $action) {

                            // Si el usuario es administrador, tiene acceso total
                            if (Yii::$app->user->can('admin')) {
                                return true;
                            }

                            $actionId = $action->id;

                            // Acciones de solo lectura: index y view
                            if ($actionId === 'index' || $actionId === 'view') {
                                return Yii::$app->user->can('viewer');
                            }

                            // Acciones de gestión: crear, actualizar y eliminar
                            if (in_array($actionId, ['create', 'update', 'delete'])) {
                                return Yii::$app->user->can('editor');
                            }

                            return false;
                        }
                    ],
                ],
            ],

            // Filtro para restringir métodos HTTP
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'], // Eliminar solo por POST
                ],
            ],
        ];
    }

    /**
     * Lista los productos
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra el detalle de un producto
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea un nuevo producto
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Producto creado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un producto existente
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Producto actualizado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un producto
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Producto eliminado correctamente.');

        return $this->redirect(['index']);
    }

    /**
     * Busca un modelo Product por su ID
     * @throws NotFoundHttpException si no existe
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
