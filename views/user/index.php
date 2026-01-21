<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-plus"></i>', ['create'], [
            'class' => 'btn btn-success shadow-sm rounded-circle', 
            'title' => 'Crear Usuario',
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

            <div class="col-md-4">
                <?= $form->field($searchModel, 'username')->textInput(['placeholder' => 'Usuario', 'class' => 'form-control'])->label(false) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($searchModel, 'email')->textInput(['placeholder' => 'Email', 'class' => 'form-control'])->label(false) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'status')->dropDownList([
                    User::STATUS_ACTIVE => 'Activo',
                    User::STATUS_INACTIVE => 'Inactivo', 
                    User::STATUS_DELETED => 'Eliminado'
                ], ['prompt' => 'Estado', 'class' => 'form-select'])->label(false) ?>
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
                // 'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-borderless align-middle'],
                'headerRowOptions' => ['class' => 'border-bottom'],
                'summary' => '<div class="text-muted mb-3">Mostrando <strong>{begin}-{end}</strong> de <strong>{totalCount}</strong> usuarios.</div>',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'id',
                        'contentOptions' => ['style' => 'width: 60px;'],
                    ],
                    [
                        'attribute' => 'username',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<div class="fw-bold text-dark">' . Html::encode($model->username) . '</div>';
                        }
                    ],
                    'email:email',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $class = match($model->status) {
                                User::STATUS_ACTIVE => 'success',
                                User::STATUS_INACTIVE => 'warning',
                                User::STATUS_DELETED => 'danger',
                                default => 'secondary'
                            };
                            $label = match($model->status) {
                                User::STATUS_ACTIVE => 'Activo',
                                User::STATUS_INACTIVE => 'Inactivo',
                                User::STATUS_DELETED => 'Eliminado',
                                default => 'Desconocido'
                            };
                            return "<span class=\"badge bg-{$class}\">{$label}</span>";
                        },
                        'filter' => [
                            User::STATUS_ACTIVE => 'Activo',
                            User::STATUS_INACTIVE => 'Inactivo',
                            User::STATUS_DELETED => 'Eliminado',
                        ],
                    ],
            
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Acciones',
                        'template' => '{view} {update} {assignment} {delete}',
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
                                return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                    'class' => 'btn btn-sm btn-light text-info', 
                                    'title' => 'Editar',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'top'
                                ]);
                            },
                            'assignment' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-user-tag"></i>', ['assignment', 'id' => $model->id], [
                                    'class' => 'btn btn-sm btn-light text-warning', 
                                    'title' => 'Roles',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'top'
                                ]);
                            },
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-light text-danger btn-delete-confirm', 
                                    'title' => 'Eliminar',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'data-confirm-message' => '¿Estás seguro de que deseas eliminar este usuario?'
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>


</div>