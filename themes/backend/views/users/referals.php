<?php
$title_ = Yii::t('backend', 'Юзеры');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    $title_ => array('/backend/users/index'),
    $user->login . ' - ' . Yii::t('backend', 'Рефералы'),
);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<table class="table">
    <thead>
        <tr>
            <th width="5%">ID</th>
            <th><?php echo Yii::t('backend', 'Логин') ?></th>
            <th width="20%"><?php echo Yii::t('backend', 'Email') ?></th>
            <th width="20%"><?php echo Yii::t('backend', 'Баланс') ?></th>
            <th width="20%"><?php echo Yii::t('backend', 'Дата создания') ?></th>
            <th width="12%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($data = $dataProvider->getData()) { ?>
            <?php foreach($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo CHtml::link($row->referalInfo->login, array('/backend/users/view', 'user_id' => $row->referalInfo->user_id)) ?></td>
                    <td><?php echo $row->referalInfo->email ?></td>
                    <td><?php echo CHtml::link($row->referalInfo->profile->balance, array('/backend/transactions/index', 'user_id' => $row->referalInfo->user_id), array('title' => Yii::t('main', 'История пополнений'), 'rel' => 'tooltip')) ?></td>
                    <td><?php echo formatDate($row->created_at) ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <!-- <li><?php echo HTML::link('', array('/backend/users/view', 'user_id' => $row->referalInfo->user_id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li> -->
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6"><?php echo Yii::t('backend', 'Нет данных') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
