<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\RbacAudit;
use yii\helpers\ArrayHelper;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            // Control de acceso: solo el rol administrador puede acceder
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'], // Solo administradores
                    ],
                ],
            ],

            // Restricción de métodos HTTP
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'], // Eliminar solo mediante POST
                ],
            ],
        ];
    }

    /**
     * Lista todos los usuarios
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra el detalle de un usuario
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea un nuevo usuario
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {

            // Manejo de contraseña
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }

            // Genera la clave de autenticación
            $model->generateAuthKey();

            if ($model->save()) {

                // Asignación de roles
                $auth = Yii::$app->authManager;
                $roles = Yii::$app->request->post('roles', []);

                foreach ($roles as $roleName) {
                    $role = $auth->getRole($roleName);
                    if ($role) {
                        $auth->assign($role, $model->id);
                        $this->logAudit($model->id, 'ASSIGN', $roleName);
                    }
                }

                Yii::$app->session->setFlash('success', 'Usuario creado correctamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un usuario existente
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            // Actualiza la contraseña solo si se proporciona una nueva
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }

            if ($model->save()) {

                // Actualización de roles:
                $auth = Yii::$app->authManager;

                // Obtener roles actuales antes de borrar
                $currentRoles = array_keys($auth->getRolesByUser($model->id));
                $newRoles = Yii::$app->request->post('roles', []);

                // Calcular diferencias
                $toAssign = array_diff($newRoles, $currentRoles);
                $toRevoke = array_diff($currentRoles, $newRoles);

                // Aplicar cambios y Log
                foreach ($toRevoke as $roleName) {
                    $role = $auth->getRole($roleName);
                    if ($role) {
                        $auth->revoke($role, $model->id);
                        $this->logAudit($model->id, 'REVOKE', $roleName);
                    }
                }

                foreach ($toAssign as $roleName) {
                    $role = $auth->getRole($roleName);
                    if ($role) {
                        $auth->assign($role, $model->id);
                        $this->logAudit($model->id, 'ASSIGN', $roleName);
                    }
                }

                Yii::$app->session->setFlash('success', 'Usuario actualizado correctamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un usuario
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Usuario eliminado correctamente.');

        return $this->redirect(['index']);
    }

    /**
     * Administra la asignación de roles para un usuario
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException si el usuario no existe
     */
    public function actionAssignment(int $id)
    {
        $model = $this->findModel($id);
        $auth = Yii::$app->authManager;

        // Obtiene todos los roles existentes
        $allRoles = $auth->getRoles();

        // Obtiene los roles asignados al usuario
        $userRoles = $auth->getRolesByUser($id);
        $assignedRoleNames = array_keys($userRoles);

        if (Yii::$app->request->isPost) {
            $newRoles = Yii::$app->request->post('roles', []);

            // 1. Revocar roles eliminados
            foreach ($assignedRoleNames as $roleName) {
                if (!in_array($roleName, $newRoles)) {
                    $role = $auth->getRole($roleName);
                    $auth->revoke($role, $id);
                    $this->logAudit($id, 'REVOKE', $roleName);
                }
            }

            // 2. Asignar nuevos roles
            foreach ($newRoles as $roleName) {
                if (!in_array($roleName, $assignedRoleNames)) {
                    $role = $auth->getRole($roleName);
                    if ($role) {
                        $auth->assign($role, $id);
                        $this->logAudit($id, 'ASSIGN', $roleName);
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Roles actualizados correctamente.');
            return $this->refresh();
        }

        return $this->render('assignment', [
            'model' => $model,
            'allRoles' => $allRoles,
            'assignedRoleNames' => $assignedRoleNames,
        ]);
    }

    /**
     * Registra una auditoría de cambios en RBAC
     */
    private function logAudit(int $userId, string $action, string $itemName): void
    {
        $log = new RbacAudit();
        $log->user_id = $userId;
        $log->action = $action;
        $log->item_name = $itemName;
        $log->changed_by = Yii::$app->user->id;
        $log->save();
    }

    /**
     * Busca un usuario por su ID
     *
     * @throws NotFoundHttpException si no existe
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
