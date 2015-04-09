<?php
$title_ = Yii::t('main', 'Удаление HWID');
$this->pageTitle = $title_;

$this->breadcrumbs=array(
    Yii::t('main', 'Услуги') => array('/cabinet/services/index'),
    $title_
);
?>

<?php if($gs['services_remove_hwid_allow']) { ?>
    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <?php echo CHtml::beginForm() ?>
        <div class="button-group center">
            <button class="button" type="submit">
                <span><?php echo Yii::t('main', 'Удалить HWID') ?></span>
            </button>
        </div>
    <?php echo CHtml::endForm() ?>
<?php } else { ?>
    <div class="alert alert-info">
        <?php echo Yii::t('main', 'Услуга отключена.') ?>
    </div>
<?php } ?>