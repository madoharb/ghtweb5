<?php
/**
 * @var AuthHistoryController $this
 * @var CActiveDataProvider $dataProvider
 * @var UsersAuthLogs[] $data
 */

$title_ = Yii::t('main', 'История авторизаций');
$this->pageTitle = $title_;
$this->breadcrumbs=array($title_);
?>

<div class="entry">
    <div class="scroll-pane">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>IP</th>
                    <th><?php echo Yii::t('main', 'Браузер') ?></th>
                    <th><?php echo Yii::t('main', 'Доступ') ?></th>
                    <th><?php echo Yii::t('main', 'Дата') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($data = $dataProvider->getData()) { ?>
                    <?php foreach($data as $i => $row) { ?>
                        <tr>
                            <td><?php echo ++$i ?></td>
                            <td><?php echo CHtml::link($row->ip, getLocationLinkByIp($row->ip), array('target' => '_blank')) ?></td>
                            <td><?php echo e($row->user_agent) ?></td>
                            <td><span style="color: <?php echo ($row->status == UsersAuthLogs::STATUS_AUTH_SUCCESS ? 'green' : 'red') ?>;"><?php echo $row->getStatus() ?></span></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($row->created_at)) ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5"><?php echo Yii::t('main', 'Нет данных.') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>