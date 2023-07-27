<?php
/** @var $this \yii\web\View */
/** @var $component \common\components\GdsCalc\GdsCalc */
/** @var $component_alias \common\components\GdsCalc\GdsCalc */

\bryza\assets\GdsCalcAsset::register($this);

$urlForm = \noIT\core\helpers\Url::to(['gdscalc/form']);
$urlOrder = \noIT\core\helpers\Url::to(['gdscalc/order']);
$urlCalc = \noIT\core\helpers\Url::to(['gdscalc/calc']);
$calcProgressCaption = Yii::t('app', "Идет просчет, подождите...");

$js = <<<JS
function overlayShow(el, content) {
    if (!content) {
        content = "{$calcProgressCaption}";
    }
    el.html(content);
}

function overlayHide(el) {
    
}

$('.gdscalc-container-{$component->alias}').each(function() {
  var component = $(this);
  var modal = $('#gdscalc-modal-{$component->alias}');
  var titleContainer = $('.modal-header .title', modal);
  var formContainer = $('.gdscalc-form', component);
  var orderModel = $('.gdscalc-order-modal', component);
  $('.gdscalc-models > a', component).click(function(e) {
    e.preventDefault();
    
    var model = $(this).attr('data-alias');
    
    $.ajax({
        url: '{$urlForm}',
        data: {
            component: '{$component_alias}',
            model: model,
        },
        type: 'post',
        dataType: 'json',
        success: function(data) {
          titleContainer.html(data.title);
          formContainer.html(data.form);
          $('.gdscalc-calculation', component).html('');
          modal.modal('show');
          
          $('[data-previewslide]').on('hover focus', function() {
              $('.gdscalc-formpreview').css('background-position-x', -1 * ($(this).attr('data-previewslide') * 350));
          });
          
          $(document).on('keyup', 'input', function(e){
              $(this).val($(this).val().replace(/[,]/g, '.'));
          });
          
          formContainer.on('beforeSubmit', '.gdscalc-form'+ model, function() {
              overlayShow($('.gdscalc-calculation', component));
              $.post(
                  '{$urlCalc}',
                  $(this).serializeArray(),
                  function(html) {
                    overlayHide();
                    $('.gdscalc-calculation', component).html(html);
                    
                    $('.add-to-cart', component).click(function(e) {
                      var button = $(this);
                      console.log(button.attr('data-data'));
                      $.ajax({
                        url: '{$urlOrder}',
                        data: {
                            data: button.attr('data-data')
                        },
                        type: 'post',
                        success: function (html) {
                            modal.modal('hide');
                            orderModel.modal('show');
                            $('.modal-body', orderModel).html(html);
                        },
                        error: function (e) {
                          alert('Ошибка отправки данных.');
                        }
                      });
                    });
                  }
              );
              return false;
          });
        }
    });
  });
});
JS;

$this->registerJs($js);

?>

<div class="gdscalc-container gdscalc-container-<?= $component->alias ?>" data-component="<?= $component->alias ?>">
    <div style="text-align: center; font-size: large"><?= Yii::t('app', 'Выберите тип крыши и заполните форму в открывшемся окне') ?></div>
    <div class="gdscalc-models">
        <?= $this->render('_models', ['component' => $component]) ?>
    </div>
    <?php \yii\bootstrap\Modal::begin([
        'id' => 'gdscalc-modal-'. $component->alias,
        'header' => '<div class="title h3"></div>',
        'size' => \yii\bootstrap\Modal::SIZE_LARGE,
    ])?>
    <div class="gdscalc-type"></div>
    <div class="gdscalc-form"></div>
    <div class="gdscalc-calculation"></div>
    <?php \yii\bootstrap\Modal::end()?>

    <?php \yii\bootstrap\Modal::begin([
        'options' => [
            'class' => 'gdscalc-order-modal',
        ],
        'header' => '<div class="title h3">'. Yii::t('app', 'Contact details') .'</div>',
        'size' => \yii\bootstrap\Modal::SIZE_SMALL,
    ])?>
    <?php \yii\bootstrap\Modal::end()?>
</div>
