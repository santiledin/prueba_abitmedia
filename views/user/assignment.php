<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $allRoles yii\rbac\Role[] */
/* @var $assignedRoleNames string[] */

$this->title = 'Asignar Roles: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Asignaciones';
?>
<div class="user-assignment">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <h3>Seleccionar Roles</h3>

        <div class="form-group">
            <?php foreach ($allRoles as $role): ?>
                <div class="form-check custom-checkbox">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role->name ?>"
                        id="role-<?= $role->name ?>" <?= in_array($role->name, $assignedRoleNames) ? 'checked' : '' ?>
                        style="width: 1em; height: 1em;">
                    <label class="form-check-label ms-2" for="role-<?= $role->name ?>">
                        <strong><?= Html::encode($role->name === 'admin' ? 'Administrador' : ($role->name === 'editor' ? 'Editor' : ($role->name === 'viewer' ? 'Visualizador' : $role->name))) ?></strong>
                        <br>
                        <small class="text-muted"><?= Html::encode($role->description) ?></small>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="form-group mt-3 text-end">
            <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], [
                'class' => 'btn btn-secondary shadow-sm rounded-circle d-inline-flex justify-content-center align-items-center',
                'style' => 'width: 50px; height: 50px;',
                'title' => 'Regresar',
                'data-bs-toggle' => 'tooltip'
            ]) ?>
            <?= Html::submitButton('<i class="fas fa-save"></i>', [
                'class' => 'btn btn-success shadow-sm rounded-circle d-inline-flex justify-content-center align-items-center ms-2',
                'style' => 'width: 50px; height: 50px;',
                'title' => 'Guardar Asignaciones',
                'data-bs-toggle' => 'tooltip'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <hr>

    <h3>Registro de Auditoría</h3>
    <?php
    $logs = \app\models\RbacAudit::find()
        ->where(['user_id' => $model->id])
        ->orderBy(['created_at' => SORT_DESC])
        ->limit(10)
        ->all();
    ?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Acción</th>
                <th>Rol</th>
                <th>Cambiado Por</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="4">No hay cambios registrados.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td>
                            <span class="badge bg-<?= $log->action === 'ASSIGN' ? 'success' : 'danger' ?>">
                                <?= $log->action ?>
                            </span>
                        </td>
                        <td><?= Html::encode($log->item_name) ?></td>
                        <td><?= $log->changer ? Html::encode($log->changer->username) : 'System' ?></td>
                        <td><?= Yii::$app->formatter->asDatetime($log->created_at) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>