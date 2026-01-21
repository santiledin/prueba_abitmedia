<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], [
            'class' => 'btn btn-secondary shadow-sm rounded-circle',
            'title' => 'Regresar',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'top'
        ]) ?>

        <?php if (Yii::$app->user->can('editor')): ?>
            <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary shadow-sm rounded-circle',
                'title' => 'Actualizar',
                'data-bs-toggle' => 'tooltip',
                'data-bs-placement' => 'top'
            ]) ?>
            <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger shadow-sm rounded-circle',
                'title' => 'Eliminar',
                'data-bs-toggle' => 'tooltip',
                'data-bs-placement' => 'top',
                'data' => [
                    'confirm' => '¿Estás seguro de que deseas eliminar este elemento?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sku',
            'name',
            'description:ntext',
            [
                'attribute' => 'price',
                'value' => '$ ' . number_format($model->price, 2),
            ],
            'stock',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>