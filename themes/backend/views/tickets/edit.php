<?php
/**
 * @var TicketsController $this
 * @var Tickets $ticket
 * @var TicketsAnswers $model
 * @var CActiveDataProvider $answersDataProvider
 * @var TicketsAnswers[] $answers
 * @var TicketsAnswers $answer
 * @var ActiveForm $form
 */

$title__ = Yii::t('backend', 'Тикеты');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/' . $this->getId() . '/index'),
    $ticket->title . ' - ' . Yii::t('backend', 'Просмотр')
) ?>

<h3>Информация о тикете</h3>
<table class="table">
    <tbody>
        <tr>
            <td width="30%"><?php echo Yii::t('backend', 'Автор') ?></td>
            <td width="70%"><?php echo CHtml::link($ticket->user->login,
                    array('/backend/users/view', 'user_id' => $ticket->user->user_id),
                    array('title' => Yii::t('backend', 'Перейти к просмотру пользователя'), 'target' => '_blank', 'rel' => 'tooltip')) ?></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('backend', 'Категория') ?></td>
            <td><?php echo CHtml::encode($ticket->category->title) ?></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('backend', 'Приоритет') ?></td>
            <td><?php echo $ticket->getPriority() ?></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('backend', 'Дата инцидента') ?></td>
            <td><?php echo CHtml::encode($ticket->date_incident) ?></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('backend', 'Имя персонажа') ?></td>
            <td><?php echo e($ticket->char_name) ?></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('backend', 'Дата создания тикета') ?></td>
            <td><?php echo $ticket->getCreatedAt() ?></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('backend', 'Дата последнего ответа') ?></td>
            <td><?php echo $ticket->getUpdatedAt() ?></td>
        </tr>
    </tbody>
</table>

<h3><?php echo Yii::t('backend', 'Ответы') ?></h3>
<hr>

<?php if($answers = $answersDataProvider->getData()) { ?>

    <?php foreach($answers as $answer) { ?>
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo Yii::t('backend', 'Дата') ?>: <?php echo $answer->getCreatedAt() ?>
                <?php echo Yii::t('backend', 'Автор') ?>: <?php echo CHtml::link($answer->userInfo->login,
                    array('/backend/users/view', 'user_id' => $ticket->user->user_id),
                    array('title' => Yii::t('backend', 'Перейти к просмотру пользователя'), 'target' => '_blank', 'rel' => 'tooltip')) ?> (<?php echo $answer->userInfo->role ?>)</div>
            <div class="panel-body">
                <?php echo nl2br(CHtml::encode($answer->text)) ?>
            </div>
        </div>
    <?php } ?>

    <?php $this->widget('CLinkPager', array(
        'pages' => $answersDataProvider->getPagination(),
    )) ?>

<?php } else { ?>
    <?php echo Yii::t('backend', 'Нет данных.') ?>
<?php } ?>

<h3><?php echo Yii::t('backend', 'Добавить ответ') ?></h3>

<?php if($ticket->status == 1) { ?>

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
            <div class="col-lg-9">
                <?php echo $form->textArea($model, 'text', array('placeholder' => $model->getAttributeLabel('text'), 'class' => 'form-control')) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary"><?php echo Yii::t('backend', 'Добавить ответ') ?></button>
            </div>
        </div>

    <?php $this->endWidget() ?>
<?php } else { ?>
    <?php echo Yii::t('backend', 'Нельзя добавить ответ в закрытый тикет.') ?>
<?php } ?>