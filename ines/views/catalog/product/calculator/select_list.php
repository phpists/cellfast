<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\ProductEntity
 */
?>
<div id="select_list_items" class="checkout modal fade">
    <div class="checkout__block">
        <div class="checkout__block__head">
            <div data-dismiss="modal" aria-label="Close" class="checkout__block__header">Выбор из списка</div>
            <!--			<div class="checkout__block__info">-->
            <!--				<div class="checkout__block__info__text col-30"><strong>Всего товаров:<span>27 шт.</span></strong></div>-->
            <!--				<div class="checkout__block__info__text col-40"><strong>На самму:<span>34 678 грн.</span></strong></div>-->
            <!--				<div class="checkout__block__info__button col-30"><a href="javascript:;" class="btn btn_blue">Заказать</a></div>-->
            <!--			</div>-->
        </div>
        <div class="checkout__block__table">
            <table>
                <thead>
                <tr>
                    <th class="name"> Наименование</th>
                    <th class="sku">Артикул</th>
                    <?php foreach ($model->definedFeatures as $feature) :?>
                        <th class="param"><?= $feature['entity']->name?></th>
                    <?php endforeach?>
                    <th class="button">&nbsp;</th>
                </tr>
                </thead>
            </table>
            <div class="scroll">
                <div class="checkout__block__table_items">
                    <div class="checkout__block__table__items__body">
                        <?php foreach ($model->items as $item) :?>
                            <div class="checkout__block__table__items__body__item">
                                <div class="item__name"><?= $item->name ?></div>
                                <div class="item__sku align-center"><?= $item->sku ?></div>
                                <?php foreach ($model->definedFeatures as $feature) :?>
                                    <div class="item__param align-center"><?= $item->featureGroupedValues[$feature['entity']->id]->value_label?></div>
                                <?php endforeach?>
                                <div class="item__amount align-center">
                                    <div class="checkout__block__table__counter">
                                        <input type="number" value="0" id="product_table_1"/><a href="javascript:;" class="_plus"></a><a href="javascript:;" class="_minus"></a>
                                    </div>
                                </div>
                                <div class="item__button align-center calculator__dropdown" data-params='<?= "[{\"count\": {$model->item->packages['pallet']},\"name\": \"Паллет\"},{\"count\": {$model->item->packages['packet']},\"name\": \"Упаковка\"}]" ?>'><a href="javascript:;" data-target="#product_table_1" class="dropdown-toggle checkout__block__table__calc">
                                        <svg class="svg-icon calc-icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#calc-icon"></use>
                                        </svg></a></div>
                            </div>
                        <?php endforeach?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
