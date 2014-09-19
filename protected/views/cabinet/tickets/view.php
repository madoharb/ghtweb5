<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $answersDataProvider
 * @var TicketsAnswers[] $answers
 * @var TicketsAnswers $model
 * @var Tickets $ticket
 */

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.views.assets'), FALSE, -1, YII_DEBUG);

$title_ = Yii::t('main', 'Поддержка - просмотр тикета');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    Yii::t('main', 'Поддержка') => array('/cabinet/tickets/index'),
    Yii::t('main', 'Тикет - :ticket_name', array(':ticket_name' => e($ticket->title)))
);
?>

<?php if($ticket->isStatusOff()) { ?>
    <div class="alert alert-info">
        <?php echo Yii::t('main', 'Тикет закрыт') ?>
    </div>
<?php } ?>

<div class="entry">
    <div class="scroll-pane">
        <?php if($answers = $answersDataProvider->getData()) { ?>
            <ul class="list-unstyled answers clearfix">
                <?php foreach($answers as $answer) { ?>
                    <li class="clearfix">
                        <figure>
                            <?php if(user()->getId() == $answer->user_id) { ?>
                                <?php echo CHtml::image($assetsUrl . '/images/tiket_03.png'); ?>
                            <?php } else { ?>
                                <?php echo CHtml::image($assetsUrl . '/images/tiket_24.png'); ?>
                            <?php } ?>
                        </figure>
                        <div class="info">
                            <span class="author"><?php echo (user()->getId() == $answer->user_id ? Yii::t('main', 'Вы') : Yii::t('main', 'Админ')) ?></span>
                            <p class="date"><?php echo $answer->getDate() ?></p>
                            <p class="text"><?php echo nl2br(e($answer->text)) ?></p>
                        </div>
                    </li>
                <?php } ?>
            </ul>

            <?php $this->widget('CLinkPager', array(
                'pages' => $answersDataProvider->getPagination(),
            )) ?>

        <?php } else { ?>
            <?php echo Yii::t('main', 'Нет данных.') ?>
        <?php } ?>
    </div>
</div>

<?php if($ticket->status == ActiveRecord::STATUS_ON) { ?>

    <h2><?php echo Yii::t('main', 'Добавить ответ') ?></h2>
    <?php $form = $this->beginWidget('ActiveForm', array(
        'id' => $this->getId() . '-form',
        'htmlOptions' => array(
            'class' => 'form-horizontal',
        )
    )) ?>

        <?php echo $form->errorSummary($model) ?>

        <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'text', array('class' => 'col-lg-3 control-label')) ?>
            <div class="field">
                <?php echo $form->textArea($model, 'text', array('placeholder' => $model->getAttributeLabel('text'), 'class' => 'form-control')) ?>
            </div>
        </div>

        <div class="button-group center">
            <button type="submit" class="button">
                <span><?php echo Yii::t('main', 'Ответить') ?></span>
            </button>
        </div>

    <?php $this->endWidget() ?>

<?php } ?>