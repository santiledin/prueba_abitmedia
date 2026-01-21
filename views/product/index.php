<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-plus"></i>', ['create'], [
            'class' => 'btn btn-success shadow-sm rounded-circle',
            'title' => 'Crear Producto',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'left'
        ]) ?>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['data-pjax' => 1, 'class' => 'row g-3'],
            ]); ?>

            <div class="col-md-3">
                <?= $form->field($searchModel, 'sku')->textInput(['placeholder' => 'SKU', 'class' => 'form-control'])->label(false) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Nombre', 'class' => 'form-control'])->label(false) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'min_price')->textInput(['placeholder' => 'Precio Min', 'type' => 'number'])->label(false) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'max_price')->textInput(['placeholder' => 'Precio Max', 'type' => 'number'])->label(false) ?>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <?= Html::submitButton('<i class="fas fa-search"></i>', [
                    'class' => 'btn btn-primary w-100',
                    'title' => 'Buscar',
                    'data-bs-toggle' => 'tooltip'
                ]) ?>
                <?= Html::a('<i class="fas fa-undo"></i>', ['index'], [
                    'class' => 'btn btn-secondary w-100',
                    'title' => 'Resetear',
                    'data-pjax' => 0,
                    'data-bs-toggle' => 'tooltip'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="grid-view-card">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel, // Usamos el form de arriba
                'tableOptions' => ['class' => 'table table-hover table-borderless align-middle'],
                'headerRowOptions' => ['class' => 'border-bottom'],
                'summary' => '<div class="text-muted mb-3">Mostrando <strong>{begin}-{end}</strong> de <strong>{totalCount}</strong> productos.</div>',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'sku',
                        'format' => 'raw',
                        'contentOptions' => ['class' => 'fw-bold text-secondary'],
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model) {
                        return '<div class="fw-bold text-dark">' . Html::encode($model->name) . '</div>';
                    }
                    ],
                    [
                        'attribute' => 'price',
                        'value' => function ($model) {
                        return '$ ' . number_format($model->price, 2);
                    }
                    ],
                    [
                        'attribute' => 'stock',
                        'format' => 'raw',
                        'value' => function ($model) {
                        $badgeClass = $model->stock < 10 ? 'danger' : ($model->stock < 50 ? 'warning' : 'success');
                        return "<span class=\"badge bg-{$badgeClass}\">{$model->stock}</span>";
                    }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Acciones',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                'class' => 'btn btn-sm btn-light text-primary',
                                'title' => 'Ver',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'top'
                            ]);
                        },
                            'update' => function ($url) {
                            // Helper logic: Check if user has role or permission 'updateProduct'
                            // For simplicity assuming role-based checks if permission is not set up
                            if (Yii::$app->user->can('editor') || Yii::$app->user->can('admin')) {
                                return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                    'class' => 'btn btn-sm btn-light text-info',
                                    'title' => 'Editar',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'top'
                                ]);
                            }
                            return '';
                        },
                            'delete' => function ($url) {
                            if (Yii::$app->user->can('admin')) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-light text-danger btn-delete-confirm',
                                    'title' => 'Eliminar',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'data-confirm-message' => '¿Estás seguro de que deseas eliminar este producto?'
                                ]);
                            }
                            return '';
                        },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>


</div>