<?php
/**
 * @var LoginServersController $this
 * @var CArrayDataProvider $dataProvider
 * @var array $data
 */

$title_ = Yii::t('backend', 'Логин сервера');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    $title_ => array('/backend/loginServers/index'),
    $ls->name . ' - ' . Yii::t('backend', 'аккаунты'),
);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => array('/backend/' . $this->getId() . '/index'),
)) ?>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th><?php echo Yii::t('backend', 'Login') ?></th>
                <th width="15%"><?php echo Yii::t('backend', 'Last Active') ?></th>
                <th width="15%"><?php echo Yii::t('backend', 'Access Level') ?></th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $i => $row) { ?>
                    <tr>
                        <td><?php echo getNumberForPagination(++$i, $perPage) ?></td>
                        <td><?php echo $row['login'] ?></td>
                        <td><?php echo ($row['last_active'] ? date('Y-m-d H:i', $row['last_active']) : '-') ?></td>
                        <td><?php echo $row['access_level'] ?></td>
                        <td>
                            <!-- <ul class="actions list-unstyled">
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/edit', 'ls_id' => $ls->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            </ul> -->
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>