<?php
/**
 * @var UsersController $this
 * @var Users $user
 * @var UsersAuthLogs $model
 * @var CActiveDataProvider $dataProvider
 * @var UsersAuthLogs[] $data
 * @var ActiveForm $form
 */

$title_ = Yii::t('backend', 'Юзеры');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    $title_ => array('/backend/users/index'),
    $user->login . ' - ' . Yii::t('backend', 'История авторизаций'),
);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => array('/backend/users/authHistory', 'user_id' => $user->user_id),
)) ?>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">IP</th>
                <th>User Agent</th>
                <th width="13%"><?php echo Yii::t('backend', 'Авторизация') ?></th>
                <th width="13%"><?php echo Yii::t('backend', 'Дата') ?></th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $form->textField($model, 'id', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'ip', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'user_agent', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control input-sm')) ?></td>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="<?php echo Yii::t('backend', 'Искать') ?>"></button>
                    <?php echo HTML::link('', array('/backend/users/authHistory', 'user_id' => $user->user_id), array('class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => Yii::t('backend', 'Сбросить'))) ?>
                </td>
            </tr>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $row) { ?>
                    <tr>
                        <td><?php echo $row->id ?></td>
                        <td><?php echo CHtml::link($row->ip, getLocationLinkByIp($row->ip), array('title' => Yii::t('main', 'Информация о IP'), 'rel' => 'tooltip', 'target' => '_blank')) ?></td>
                        <td><?php echo e($row->user_agent) ?></td>
                        <td><span style="color: <?php echo $row->status == UsersAuthLogs::STATUS_AUTH_SUCCESS ? 'green' : 'red' ?>;"><?php echo $row->getStatus() ?></span></td>
                        <td><?php echo $row->getCreatedAt() ?></td>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
