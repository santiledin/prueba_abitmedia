<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Iniciar Sesión';
?>
<div class="site-login d-flex align-items-center justify-content-center" style="min-height: 80vh;">

    <div class="card shadow-lg border-0" style="width: 100%; max-width: 450px;">
        <div class="card-header bg-primary text-white text-center py-4" style="border-radius: 0.75rem 0.75rem 0 0;">
            <h3 class="mb-0 text-white">ABITMEDIA</h3>
        </div>
        <div class="card-body p-5">
            <p class="text-center text-muted mb-4">Ingrese sus credenciales para acceder al sistema.</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
            ]); ?>

            <div class="mb-3">
                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Usuario o Correo', 'class' => 'form-control form-control-lg'])->label(false) ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Contraseña', 'class' => 'form-control form-control-lg'])->label(false) ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"form-check\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'class' => 'form-check-input'
                ]) ?>
            </div>

            <div class="d-grid gap-2">
                <?= Html::submitButton('<i class="fas fa-sign-in-alt"></i>', [
                    'class' => 'btn btn-primary btn-lg btn-block shadow-sm rounded-circle p-3 mx-auto',
                    'style' => 'width: 80px; height: 80px;',
                    'name' => 'login-button',
                    'title' => 'Iniciar Sesión',
                    'data-bs-toggle' => 'tooltip'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="card-footer text-center bg-light py-3 text-muted" style="border-radius: 0 0 0.75rem 0.75rem;">
            <small>Admin: <strong>admin/admin123</strong></small>
        </div>
    </div>
</div>