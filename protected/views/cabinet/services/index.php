<?php
$title_ = Yii::t('main', 'Услуги');
$this->pageTitle = $title_;
?>
<?php
$this->breadcrumbs=array($title_);
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<div class="user-info">
	<p><?php echo CHtml::link(Yii::t('main', 'Премиум аккаунт'), array('/cabinet/services/premium')) ?></p>
	<p><?php echo CHtml::link(Yii::t('main', 'Удаление HWID'), array('/cabinet/services/removeHwid')) ?></p>
</div>