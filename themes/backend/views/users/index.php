<?php
/**
 * @var UsersController $this
 * @var ActiveForm $form
 * @var CActiveDataProvider $dataProvider
 * @var Users[] $data
 * @var Users $model
 */

$title_ = Yii::t('backend', 'Юзеры');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => array('/backend/' . $this->getId() . '/index'),
)) ?>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th><?php echo $model->getAttributeLabel('login') ?></th>
                <th width="20%"><?php echo $model->getAttributeLabel('email') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'Баланс') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'Рефералов') ?></th>
                <th width="10%"><?php echo $model->getAttributeLabel('ls_id') ?></th>
                <th width="15%"><?php echo $model->getAttributeLabel('created_at') ?></th>
                <th width="12%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $form->textField($model, 'user_id', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'login', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'email', array('class' => 'form-control input-sm')) ?></td>
                <td></td>
                <td></td>
                <td><?php echo $form->dropDownList($model, 'ls_id', Chtml::listData(Ls::model()->not_deleted()->findAll(), 'id', 'name'), array('class' => 'form-control input-sm', 'empty' => Yii::t('backend', 'Выбрать'))) ?></td>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="<?php echo Yii::t('backend', 'Искать') ?>" rel="tooltip"></button>
                    <?php echo HTML::link('', array('/backend/' . $this->getId() . '/index'), array('class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => Yii::t('backend', 'Сбросить'), 'rel' => 'tooltip')) ?>
                </td>
            </tr>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $i => $row) { ?>
                    <tr>
                        <td><?php echo $row->user_id ?></td>
                        <td><?php echo e($row->login) ?></td>
                        <td><?php echo $row->email ?></td>
                        <td><?php echo CHtml::link(formatCurrency($row->profile->balance, FALSE), array('/backend/users/transactionHistory', 'user_id' => $row->user_id), array('title' => Yii::t('main', 'История пополнений'), 'rel' => 'tooltip')) ?></td>
                        <td><?php echo CHtml::link(count($row->referals), array('/backend/users/referals', 'user_id' => $row->user_id), array('title' => Yii::t('main', 'Список рефералов'), 'rel' => 'tooltip')) ?></td>
                        <td><?php echo CHtml::link(e($row->ls->name), array('/backend/loginServers/form', 'ls_id' => $row->ls->getPrimaryKey()), array('title' => Yii::t('main', 'Просмотр сервера'), 'rel' => 'tooltip')) ?></td>
                        <td><?php echo $row->getCreatedAt() ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle glyphicon glyphicon-cog" type="button" id="dropdownMenu<?php echo $i ?>" data-toggle="dropdown"></button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu<?php echo $i ?>">
                                    <li><?php echo HTML::link(Yii::t('backend', 'Просмотр'), array('/backend/' . $this->getId() . '/view', 'user_id' => $row->getPrimaryKey())) ?></li>
                                    <li><?php echo HTML::link(Yii::t('backend', 'История авторизаций'), array('/backend/' . $this->getId() . '/authHistory', 'user_id' => $row->getPrimaryKey())) ?></li>
                                    <li><?php echo HTML::link(Yii::t('backend', 'История покупок в магазине'), array('/backend/' . $this->getId() . '/itemPurchaseLog', 'user_id' => $row->getPrimaryKey())) ?></li>
                                    <li><?php echo HTML::link(Yii::t('backend', 'История пополнений баланса'), array('/backend/' . $this->getId() . '/transactionHistory', 'user_id' => $row->getPrimaryKey())) ?></li>
                                    <li><?php echo HTML::link(Yii::t('backend', 'Игроки которых привел'), array('/backend/' . $this->getId() . '/referals', 'user_id' => $row->getPrimaryKey())) ?></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="8"><?php echo Yii::t('backend', 'Нет данных') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
