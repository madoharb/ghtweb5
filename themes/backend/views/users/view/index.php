<?php
/**
 * @var UsersController $this
 * @var Users $model
 * @var UserBonuses $bonus
 */

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('webroot.themes.backend.assets'), FALSE, -1, YII_DEBUG);

js($assetsUrl . '/js/users/view.js', CClientScript::POS_END);


$title_ = Yii::t('backend', 'Юзеры');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    $title_ => array('/backend/users/index'),
    $model->login . ' - ' . Yii::t('backend', 'Просмотр'),
) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<table class="table table-striped table-bordered">
    <tbody>
        <tr>
            <td width="25%"><b><?php echo $model->getAttributeLabel('login') ?></b></td>
            <td width="25%"><?php echo $model->login ?></td>
            <td width="25%"><b><?php echo $model->getAttributeLabel('email') ?></b></td>
            <td width="25%"><?php echo $model->email ?></td>
        </tr>
        <tr>
            <td><b><?php echo $model->getAttributeLabel('activated') ?></b></td>
            <td><?php echo $model->getActivatedStatus() ?></td>
            <td><b><?php echo $model->getAttributeLabel('referer') ?></b></td>
            <td><?php echo $model->referer ?></td>
        </tr>
        <tr>
            <td><b><?php echo $model->getAttributeLabel('role') ?></b></td>
            <td><?php echo $model->getRole() ?></td>
            <td><b><?php echo $model->profile->getAttributeLabel('balance') ?></b></td>
            <td><?php echo formatCurrency($model->profile->balance) ?></td>
        </tr>
        <tr>
            <td><b><?php echo $model->profile->getAttributeLabel('vote_balance') ?></b> (Не используется)</td>
            <td><?php echo $model->profile->vote_balance ?></td>
            <td><b><?php echo $model->profile->getAttributeLabel('preferred_language') ?></b></td>
            <td><?php echo $model->profile->preferred_language ?></td>
        </tr>
        <tr>
            <td><b><?php echo $model->profile->getAttributeLabel('protected_ip') ?></b></td>
            <td><?php echo ($model->profile->protected_ip && is_array($model->profile->protected_ip) ? implode(', ', $model->profile->protected_ip) : 'Привязки к IP нет') ?></td>
            <td><b><?php echo $model->profile->getAttributeLabel('phone') ?></b> (Не используется)</td>
            <td><?php echo $model->profile->phone ?></td>
        </tr>
    </tbody>
</table>

<?php echo CHtml::link(Yii::t('backend', 'Добавить бонус'), array('addBonus', 'user_id' => $model->getPrimaryKey()), array('class' => 'btn btn-xs btn-primary js-add-bonus')) ?>&nbsp;
<?php echo CHtml::link(Yii::t('backend', 'Отправить сообщение'), array('addMessage', 'user_id' => $model->getPrimaryKey()), array('class' => 'btn btn-xs btn-primary js-send-message')) ?>&nbsp;
<?php echo CHtml::link(Yii::t('backend', 'Редактировать данные юзера'), array('editData', 'user_id' => $model->getPrimaryKey()), array('class' => 'btn btn-xs btn-primary js-edit-data')) ?>

<?php if(!$model->isAdmin()) { ?>
    <?php echo CHtml::link(Yii::t('backend', 'Сделать админом'), array('changeRole', 'user_id' => $model->getPrimaryKey()), array('class' => 'btn btn-xs btn-primary')) ?>
<?php } else { ?>
    <?php echo CHtml::link(Yii::t('backend', 'Удалить статус админа'), array('changeRole', 'user_id' => $model->getPrimaryKey()), array('class' => 'btn btn-xs btn-primary')) ?>
<?php } ?>

<br/>
<br/>
<div class="alert alert-info"><?php echo Yii::t('backend', 'Админ имеет доступ только к админке сайта и его функционалу.') ?></div>

<h3><?php echo Yii::t('backend', 'Бонусы') ?></h3>
<hr>

<?php if($model->bonuses) { ?>
    <ul class="list-unstyled">
        <?php foreach($model->bonuses as $bonus) { ?>
            <li>
                <p><b><?php echo Yii::t('backend', 'Название') ?></b>:
                    <?php echo CHtml::link(e($bonus->bonusInfo->title), array('/backend/bonuses/items', 'bonus_id' => $bonus->bonus_id), array('target' => '_blank', 'rel' => 'tooltip', 'title' => Yii::t('backend', 'Перейти к просмотру бонуса'))) ?>
                    <?php echo CHtml::link(Yii::t('backend', 'Удалить бонус'), array('delBonus', 'user_id' => $model->user_id, 'bonus_id' => $bonus->id), array('class' => 'js-remove-bonus')) ?>
                </p>
                <p><b><?php echo Yii::t('backend', 'Статус') ?></b>: <span class="label label-<?php echo $bonus->bonusInfo->isStatusOn() ? 'success' : 'default' ?>"><?php echo $bonus->bonusInfo->getStatus() ?></span></p>
                <p><b><?php echo Yii::t('backend', 'Состояние') ?></b>: <span class="label label-<?php echo $bonus->status == UserBonuses::STATE_ACTIVE ? 'default' : 'success' ?>"><?php echo $bonus->getState() ?></span></p>
                <p><b><?php echo Yii::t('backend', 'Предметы') ?></b>:</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th><?php echo Yii::t('backend', 'Название') ?></th>
                            <th width="15%"><?php echo Yii::t('backend', 'Кол-во') ?></th>
                            <th width="10%"><?php echo Yii::t('backend', 'Заточка') ?></th>
                            <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($items = $bonus->bonusInfo->items) { ?>
                            <?php foreach($items as $item) { ?>
                                <tr>
                                    <td><?php echo $item->itemInfo->getIcon() ?></td>
                                    <td><?php echo $item->itemInfo->name ?></td>
                                    <td><?php echo number_format($item->count, 0, '', '.') ?></td>
                                    <td><?php echo $item->enchant ?></td>
                                    <td><span class="label label-<?php echo $item->isStatusOn() ? 'success' : 'default' ?>"><?php echo $item->getStatus() ?></span></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <hr>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p><?php echo Yii::t('backend', 'Нет данных.') ?></p>
<?php } ?>