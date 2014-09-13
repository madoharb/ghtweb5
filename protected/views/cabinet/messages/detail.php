<?php
/**
 * @var UserMessages $model
 */

$title_ = Yii::t('main', 'Личные сообщения');
$this->pageTitle = $title_;

$this->breadcrumbs=array(
    $title_ => array('/cabinet/messages/index'),
    $model->getShortMessage(5)
);
?>

<h3><?php echo Yii::t('main', 'Информация') ?></h3>
<p>
    <b><?php echo Yii::t('main', 'Дата создания') ?>:</b> <?php echo date('Y-m-d H:i', strtotime($model->created_at)) ?><br>
    <b><?php echo Yii::t('main', 'Статус') ?>:</b> <?php echo e($model->read == UserMessages::STATUS_NOT_READ ? Yii::t('main', 'Не прочитано') : Yii::t('main', 'Прочитано')) ?><br>
    <b><?php echo Yii::t('main', 'Сообщение') ?>:</b><br>
    <?php echo nl2br(e($model->message)) ?><br>
</p>
