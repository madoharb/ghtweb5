<?php
/**
 * @var BonusesController $this
 * @var Bonuses $bonus
 * @var BonusesItems $model
 * @var ActiveForm $form
 */

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('webroot.themes.' . themeName() . '.assets'), FALSE, -1, YII_DEBUG);

js($assetsUrl . '/js/typeahead.bundle.min.js', CClientScript::POS_END);
js($assetsUrl . '/js/search-items.js', CClientScript::POS_END);

$title__ = Yii::t('backend', 'Бонусы');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/bonuses/index'),
    $bonus->title => array('/backend/bonuses/items', 'bonus_id' => $bonus->id),
    (request()->getParam('id') ? Yii::t('backend', 'Редактирование') : Yii::t('backend', 'Добавление'))
);
?>

<script>
    var urlItemInfo = '<?php echo $this->createUrl('/backend/default/getItemInfo') ?>';
</script>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <style>
    .img-block
    {
        position: relative;
    }
        .img-block .img
        {
            position: absolute;
            top: 0;
            right: -20px;
            width: 32px;
            height: 32px;
        }
    </style>

    <p class="help-block" style="font-size: 13px;"><b style="color: red;">*</b> <?php echo Yii::t('backend', 'Чтобы добавить предмет достаточно начать набирать его название или ввести его ID') ?></p><br>

    <input name="old_item_id" value="<?php echo $model->item_id ?>" type="hidden"/>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'item_name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9 img-block">
            <div class="img">
                <?php if($this->getAction()->id != 'itemAdd') { ?>
                    <?php echo $model->itemInfo->getIcon() ?>
                <?php } ?>
            </div>
            <?php echo $form->textField($model, 'item_name', array('placeholder' => $model->getAttributeLabel('item_name'), 'class' => 'form-control js-item-name')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Название пишется по Русски (База синхронизирована с РУ офом)') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'item_id', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'item_id', array('placeholder' => $model->getAttributeLabel('item_id'), 'class' => 'form-control js-item-id')) ?>
            <p class="help-block"><?php echo Yii::t('backend', '57 - Адена, 4037 - Coin of luck') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'count', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'count', array('placeholder' => $model->getAttributeLabel('count'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'enchant', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'enchant', array('placeholder' => $model->getAttributeLabel('enchant'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo ($this->getAction()->id == 'itemAdd' ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить')) ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>