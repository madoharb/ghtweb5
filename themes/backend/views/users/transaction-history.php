<?php
/**
 * @var TransactionsController $this
 * @var ActiveForm $form
 * @var CActiveDataProvider $dataProvider
 * @var Transactions[] $data
 * @var Users $user
 */

$title_ = Yii::t('backend', 'История пополнения баланса');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    Yii::t('backend', 'Юзеры') => array('/backend/users/index'),
    $user->login . ' - ' . $title_,
);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id'     => $this->getId() . '-form',
    'method' => 'GET',
    'action' => array('/backend/' . $this->getId() . '/transactionHistory', 'user_id' => $user->getPrimaryKey())
)) ?>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th><?php echo $model->getAttributeLabel('payment_system') ?></th>
                <th width="15%"><?php echo $model->getAttributeLabel('user_id') ?></th>
                <th width="10%"><?php echo $model->getAttributeLabel('sum') ?></th>
                <th width="12%"><?php echo $model->getAttributeLabel('status') ?></th>
                <th width="13%"><?php echo $model->getAttributeLabel('user_ip') ?></th>
                <th width="15%"><?php echo Yii::t('backend', 'Дата') ?></th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $form->textField($model, 'id', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->dropDownList($model, 'payment_system', array('' => Yii::t('backend', 'Выбрать')) + $aggregatorsList, array('class' => 'form-control input-sm')) ?></td>
                <td></td>
                <td><?php echo $form->textField($model, 'sum', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->dropDownList($model, 'status', array('' => Yii::t('backend', 'Выбрать')) + $model->getStatusList(), array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'user_ip', array('class' => 'form-control input-sm')) ?></td>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="<?php echo Yii::t('backend', 'Искать') ?>"></button>
                    <?php echo HTML::link('', array('/backend/' . $this->getId() . '/transactionHistory', 'user_id' => $user->getPrimaryKey()), array('class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => Yii::t('backend', 'Сбросить'))) ?>
                </td>
            </tr>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $row) { ?>
                    <tr>
                        <td><?php echo $row->id ?></td>
                        <td><?php echo $row->getType() ?></td>
                        <td><?php echo (isset($row->user->login) ? CHtml::link($row->user->login, array('/backend/users/view', 'user_id' => $row->user->user_id)) : '*Unknown*') ?></td>
                        <td><?php echo formatCurrency($row->sum, FALSE) ?></td>
                        <td><span class="label <?php echo ($row->status == Transactions::STATUS_SUCCESS ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span></td>
                        <td><?php echo CHtml::link($row->user_ip, getLocationLinkByIp($row->user_ip), array('title' => Yii::t('main', 'Информация о IP'), 'rel' => 'tooltip', 'target' => '_blank')) ?></td>
                        <td><?php echo $row->getCreatedAt() ?></td>
                        <td></td>
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
