<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\ProductEntity
 */
?>
<div id="absolute_calculator" class="calculator__dropdown__absolute">
    <div class="dropdown-calculator calculator__dropdown__box">
		<div class="calculator__dropdown__box__header">
			<div><?=Yii::t('app', 'Калькулятор'); ?></div><a href="javascript:;" class="close-button"></a>
		</div>
		<div class="calculator__dropdown__box__body">
			<div class="calculator_row">
				<div class="calculator_cell dropdown__select_container">
					<div class="dropdown__select">
						<label data-count="0" data-target="none" class="form-control calculate_size">0</label>
					</div>
					<div class="dropdown__toggle"></div>
					<div class="dropdown__menu">
						<ul>
							<li data-count="<?= $model->item->packages['packet'] ?>" class="calculator__item">Паллет</li>
							<li data-count="<?= $model->item->packages['pallet'] ?>" class="calculator__item">Упаковка</li>
						</ul>
					</div>
				</div>
				<div class="calculator_cell">
					<input type="text" value="1" class="form-control calculator_packaged"/>
				</div>
			</div>
			<div class="calculator_row">
				<div class="calculator_cell">
					<label><?=Yii::t('app', 'Штук'); ?></label>
				</div>
				<div class="calculator_cell">
					<input type="text" value="0" class="form-control calculator_inpack"/>
				</div>
			</div>
			<div class="calculator_row">
				<div class="calculator_cell allwidth"><a href="javascript:;" class="btn btn_blue calculator__ok"><?=Yii::t('app', 'ОК'); ?></a></div>
			</div>
		</div>
	</div>
</div>