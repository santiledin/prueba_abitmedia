<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form row justify-content-center">
    <div class="col-12">
        <div>
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <?= $form->field($model, 'sku', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-barcode"></i></span>{input}</div>{hint}{error}'
                    ])->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'SKU-001']) ?>
                </div>
                <div class="col-md-8 mb-3">
                    <?= $form->field($model, 'name', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-box"></i></span>{input}</div>{hint}{error}'
                    ])->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Nombre del producto']) ?>
                </div>
            </div>

            <?= $form->field($model, 'description', [
                'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-align-left"></i></span>{input}</div>{hint}{error}'
            ])->textarea(['rows' => 4, 'class' => 'form-control', 'placeholder' => 'Descripción detallada...']) ?>

            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'price', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>{input}</div>{hint}{error}'
                    ])->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control', 'placeholder' => '0.00']) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'stock', [
                        'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="fas fa-warehouse"></i></span>{input}</div>{hint}{error}'
                    ])->textInput(['type' => 'number', 'class' => 'form-control', 'placeholder' => '0']) ?>
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