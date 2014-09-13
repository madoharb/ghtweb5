<?php
$title_ = Yii::t('main', 'Удаление HWID');
$this->pageTitle = $title_;

$this->breadcrumbs=array(
    Yii::t('main', 'Услуги') => array('/cabinet/services/index'),
    $title_
);
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo CHtml::beginForm() ?>
    <div class="button-group center">
    	<button class="button" type="submit">
            <span><?php echo Yii::t('main', 'Удалить HWID') ?></span>
        </button>
    </div>
<?php echo CHtml::endForm() ?>