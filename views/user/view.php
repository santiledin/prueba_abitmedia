<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

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
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => $model->status == User::STATUS_ACTIVE ? 'Activo' : ($model->status == User::STATUS_INACTIVE ? 'Inactivo' : 'Eliminado'),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>