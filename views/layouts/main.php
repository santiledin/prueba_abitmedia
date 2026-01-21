<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap"
        rel="stylesheet">
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <?php if (!Yii::$app->user->isGuest): ?>
        <header id="header">
            <?php
            NavBar::begin([
                'brandLabel' => 'ABITMEDIA',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-expand-md navbar-custom fixed-top bg-primary navbar-dark']
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'], // ms-auto aligns right
                'items' => [
                    ['label' => 'Productos', 'url' => ['/product/index']],
                    ['label' => 'Usuarios', 'url' => ['/user/index'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->can('admin')],
                    Yii::$app->user->isGuest
                    ? ['label' => 'Iniciar Sesión', 'url' => ['/site/login']]
                    : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
                    . Html::submitButton(
                        'Cerrar Sesión (' . Yii::$app->user->identity->username . ')',
                        [
                            'class' => 'nav-link btn btn-link logout text-white border-0',
                            'title' => 'Salir',
                        ]
                    )
                    . Html::endForm()
                    . '</li>'
                ]
            ]);
            NavBar::end();
            ?>
        </header>
    <?php endif; ?>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <!-- Breadcrumbs removed -->
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; ABITMEDIA <?= date('Y') ?></span>
        </div>
    </footer>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este elemento?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary shadow-sm rounded-circle" data-bs-dismiss="modal"
                        title="Regresar" data-bs-toggle="tooltip"><i class="fas fa-arrow-left"></i></button>
                    <button type="button" class="btn btn-danger shadow-sm rounded-circle" id="confirmDeleteBtn"
                        title="Eliminar" data-bs-toggle="tooltip"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>