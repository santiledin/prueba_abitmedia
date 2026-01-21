<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form row justify-content-center">

    <div class="col-12">
        <div>
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'username', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-user"></i></span>{input}</div>{hint}{error}'
                    ])->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Nombre de usuario']) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'email', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-envelope"></i></span>{input}</div>{hint}{error}'
                    ])->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'correo@ejemplo.com']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'password', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-lock"></i></span>{input}</div>{hint}{error}'
                    ])->passwordInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Contraseña'])
                        ->hint($model->isNewRecord ? 'Requerido' : 'Dejar en blanco para mantener la actual') ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'status', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-toggle-on"></i></span>{input}</div>{hint}{error}'
                    ])->dropDownList([
                                User::STATUS_ACTIVE => 'Activo',
                                User::STATUS_INACTIVE => 'Inactivo',
                            ], ['class' => 'form-select']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label"><i class="fas fa-tags me-1"></i> Roles del Usuario</label>
                    <div class="card p-3 bg-light border-0">
                        <?php
                        $roles = [
                            'admin' => 'Administrador',
                            'editor' => 'Editor',
                            'viewer' => 'Visualizador',
                        ];
                        ?>
                        <?= Html::checkboxList('roles', array_keys(Yii::$app->authManager->getRolesByUser($model->id)), $roles, [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $checkedAttr = $checked ? 'checked' : '';
                                return "<div class='form-check form-check-inline custom-checkbox'>
                                            <input class='form-check-input' type='checkbox' name='{$name}' value='{$value}' id='role_{$index}' {$checkedAttr} style='width: 1em; height: 1em;'>
                                            <label class='form-check-label ms-2' for='role_{$index}'>{$label}</label>
                                        </div>";
                            }
                        ]) ?>
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group text-end mt-4">
                <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], [
                    'class' => 'btn btn-secondary shadow-sm rounded-circle d-inline-flex justify-content-center align-items-center',
                    'style' => 'width: 50px; height: 50px;',
                    'title' => 'Regresar',
                    'data-bs-toggle' => 'tooltip'
                ]) ?>
                <?= Html::submitButton('<i class="fas fa-save"></i>', [
                    'class' => 'btn btn-success shadow-sm rounded-circle d-inline-flex justify-content-center align-items-center ms-2',
                    'style' => 'width: 50px; height: 50px;',
                    'title' => 'Guardar',
                    'data-bs-toggle' => 'tooltip'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>