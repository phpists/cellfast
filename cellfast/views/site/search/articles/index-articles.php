
<section class="content <?= $__params['id'] ?>-index">
    <div class="container">

        <?= \cellfast\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [],
        ]) ?>

        <?= \cellfast\widgets\Alert::widget() ?>

        <div class="content__title"><span><?= $this->title ?></span></div>

        <div id="<?= $__params['items-wrapper'] ?>" class="content__body <?= $__params['id'] ?>-items">
            <?= $this->render('_items', [
                'articles' => $articles,
                '__params' => $__params,
            ])?>
        </div>
    </div>
</section>